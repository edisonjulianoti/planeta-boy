<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Profile;
use App\Models\ProfileVideo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class MediaService
{
    /**
     * Handle image uploads for a profile.
     *
     * @param  Profile  $profile
     * @param  array<UploadedFile>  $images
     * @param  array<int>  $removeImageIds
     * @param  int|null  $newMainImageIndex
     * @param  int|null  $mainImageId
     * @return array<int, mixed>
     */
    public function handleImageUploads(
        Profile $profile,
        array $images = [],
        array $removeImageIds = [],
        ?int $newMainImageIndex = null,
        ?int $mainImageId = null,
    ): array {
        // Remove images marked for deletion
        if (!empty($removeImageIds)) {
            $profile->images()->whereIn('id', $removeImageIds)->delete();
        }

        $newImages = [];
        $currentOrder = $profile->images()->max('order') ?? 0;

        foreach ($images as $index => $file) {
            $path = $file->store('profiles/images', 'public');

            // Skip image if storage failed (disk full, permissions, etc.)
            if ($path === false) {
                \Log::warning('Falha ao salvar imagem no storage', [
                    'profile_id'   => $profile->id,
                    'original_name' => $file->getClientOriginalName(),
                    'size'         => $file->getSize(),
                    'mime'         => $file->getMimeType(),
                ]);
                continue;
            }

            $image = $profile->images()->create([
                'url'     => $path,
                'is_main' => false,
                'order'   => $currentOrder + $index + 1,
            ]);

            // Generate thumbnail for the uploaded image
            $thumbPath = $this->generateThumbnail($path);
            if ($thumbPath !== null) {
                $image->update(['thumb_path' => $thumbPath]);
            }

            $newImages[] = $image;
        }

        $this->resolveMainImage($profile, $newImages, $newMainImageIndex, $mainImageId);

        return $newImages;
    }

    /**
     * Generate a 300x300 WebP thumbnail from an image using GD.
     *
     * @param  string  $path  Relative path inside the public disk (e.g. 'profiles/images/abc.jpg')
     * @param  int  $width  Target width (default 300)
     * @param  int  $height  Target height (default 300)
     * @return string|null Relative path to the thumbnail, or null on failure.
     */
    public function generateThumbnail(string $path, int $width = 300, int $height = 300): ?string
    {
        try {
            $fullPath = storage_path('app/public/' . $path);

            if (!file_exists($fullPath)) {
                return null;
            }

            // Get image dimensions and type
            $size = @getimagesize($fullPath);

            if ($size === false) {
                return null;
            }

            [$origWidth, $origHeight, $type] = $size;

            // Create image resource from file based on type
            $source = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($fullPath),
                IMAGETYPE_PNG  => @imagecreatefrompng($fullPath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($fullPath),
                default        => null,
            };

            if ($source === null) {
                return null;
            }

            // Calculate center crop coordinates
            $cropWidth = min($width, $origWidth);
            $cropHeight = min($height, $origHeight);

            $srcX = max(0, (int) (($origWidth - $cropWidth) / 2));
            $srcY = max(0, (int) (($origHeight - $cropHeight) / 2));

            // Create thumbnail canvas
            $thumb = imagecreatetruecolor($cropWidth, $cropHeight);

            if ($thumb === false) {
                imagedestroy($source);

                return null;
            }

            // Preserve alpha for PNG sources
            if ($type === IMAGETYPE_PNG) {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
            }

            // Copy and resize the cropped portion (center crop)
            imagecopyresampled(
                $thumb, $source,
                0, 0,
                $srcX, $srcY,
                $cropWidth, $cropHeight,
                $cropWidth, $cropHeight
            );

            // Determine output path
            $info = pathinfo($path);
            $thumbDir = 'profiles/thumbs';
            $thumbFilename = $info['filename'] . '_thumb.webp';
            $thumbRelativePath = $thumbDir . '/' . $thumbFilename;
            $thumbFullPath = storage_path('app/public/' . $thumbRelativePath);

            // Ensure thumb directory exists
            $thumbFullDir = dirname($thumbFullPath);

            if (!is_dir($thumbFullDir)) {
                @mkdir($thumbFullDir, 0755, true);
            }

            // Save as WebP with quality 80
            $saved = imagewebp($thumb, $thumbFullPath, 80);

            // Cleanup
            imagedestroy($source);
            imagedestroy($thumb);

            return $saved ? $thumbRelativePath : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Handle local video uploads (MP4/WebM) for a profile.
     * Adds new videos without removing existing ones.
     *
     * @param  Profile  $profile
     * @param  array<UploadedFile>  $files
     * @return array<ProfileVideo>
     */
    public function handleVideoUploads(Profile $profile, array $files = []): array
    {
        $videos = [];

        foreach ($files as $file) {
            try {
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, ['mp4', 'webm'], true)) {
                    continue;
                }

                $path = $file->store('profiles/videos', 'public');

                if ($path === false) {
                    continue;
                }

                $currentOrder = $profile->videos()->max('order') ?? 0;

                try {
                    $video = $profile->videos()->create([
                        'type'    => 'local',
                        'path'    => $path,
                        'url'     => $path,
                        'is_main' => false,
                        'order'   => $currentOrder + 1,
                    ]);

                    $videos[] = $video;
                } catch (\Throwable $e) {
                    Storage::disk('public')->delete($path);
                    continue;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $videos;
    }

    /**
     * Sync all media for a profile — images and videos.
     *
     * @param  Profile  $profile
     * @param  array<UploadedFile>  $images
     * @param  array<UploadedFile>  $removeImageIds
     * @param  int|null  $newMainImageIndex
     * @param  int|null  $mainImageId
     * @param  array<UploadedFile>  $videoFiles
     * @return void
     */
    public function syncMedia(
        Profile $profile,
        array $images = [],
        array $removeImageIds = [],
        ?int $newMainImageIndex = null,
        ?int $mainImageId = null,
        array $videoFiles = [],
    ): void {
        $this->handleImageUploads(
            $profile,
            $images,
            $removeImageIds,
            $newMainImageIndex,
            $mainImageId,
        );

        if (!empty($videoFiles)) {
            $this->handleVideoUploads($profile, $videoFiles);
        }
    }

    /**
     * Resolve which image should be the main one.
     * Renumbers all image orders to avoid conflicts (main = order 0, rest = 1+).
     */
    private function resolveMainImage(
        Profile $profile,
        array $newImages,
        ?int $newMainImageIndex,
        ?int $mainImageId,
    ): void {
        // Priority 1: newly uploaded image selected as main
        if ($newMainImageIndex !== null && isset($newImages[$newMainImageIndex])) {
            $profile->images()->update(['is_main' => false]);
            $newImages[$newMainImageIndex]->update(['is_main' => true, 'order' => 0]);
            $this->renumberOrders($profile);
            return;
        }

        // Priority 2: existing image selected as main
        if ($mainImageId !== null && $profile->images()->where('id', $mainImageId)->exists()) {
            $profile->images()->update(['is_main' => false]);
            $profile->images()->where('id', $mainImageId)->update(['is_main' => true, 'order' => 0]);
            $this->renumberOrders($profile);
            return;
        }

        // Priority 3: first new image
        if (!empty($newImages)) {
            $profile->images()->update(['is_main' => false]);
            $newImages[0]->update(['is_main' => true, 'order' => 0]);
            $this->renumberOrders($profile);
            return;
        }

        // Priority 4: first existing image without main
        if (!$profile->images()->where('is_main', true)->exists()) {
            $firstImage = $profile->images()->orderBy('order')->first();
            if ($firstImage) {
                $firstImage->update(['is_main' => true, 'order' => 0]);
                $this->renumberOrders($profile);
            }
        }
    }

    /**
     * Renumber orders so main = 0 and all other images start from 1.
     */
    private function renumberOrders(Profile $profile): void
    {
        $images = $profile->images()->orderBy('order')->get();
        $nextOrder = 1;
        foreach ($images as $img) {
            if (!$img->is_main) {
                $img->update(['order' => $nextOrder]);
                $nextOrder++;
            }
        }
    }
}
