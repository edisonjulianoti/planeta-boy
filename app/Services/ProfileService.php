<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

final class ProfileService
{
    public function __construct(
        private readonly MediaService $mediaService,
    ) {}

    /**
     * Create a new profile for a user.
     *
     * @param  User  $user
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>  $images
     * @param  string|null  $videoUrl
     * @param  UploadedFile|null  $videoFile
     * @return Profile
     */
    public function create(User $user, array $data, array $images = [], ?string $videoUrl = null, ?UploadedFile $videoFile = null): Profile
    {
        $profile = $user->profile()->create([
            'name'        => $data['name'],
            'age'         => $data['age'],
            'city'        => $data['city'],
            'state'       => strtoupper($data['state']),
            'description' => $data['description'] ?? null,
            'active'      => true,
            'verified'    => false,
            'rating'      => 0,
            'views'       => 0,
        ]);

        if (!empty($images)) {
            $this->mediaService->handleImageUploads($profile, $images);
        }

        $this->mediaService->handleYouTubeVideo($profile, $videoUrl);

        if ($videoFile !== null) {
            $this->mediaService->handleVideoUpload($profile, $videoFile);
        }

        return $profile;
    }

    /**
     * Update an existing profile.
     *
     * @param  Profile  $profile
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>  $images
     * @param  array<int>  $removeImageIds
     * @param  int|null  $newMainImageIndex
     * @param  int|null  $mainImageId
     * @param  string|null  $videoUrl
     * @param  UploadedFile|null  $videoFile
     * @return Profile
     */
    public function update(
        Profile $profile,
        array $data,
        array $images = [],
        array $removeImageIds = [],
        ?int $newMainImageIndex = null,
        ?int $mainImageId = null,
        ?string $videoUrl = null,
        ?UploadedFile $videoFile = null,
    ): Profile {
        $profile->update([
            'name'        => $data['name'],
            'age'         => $data['age'],
            'city'        => $data['city'],
            'state'       => strtoupper($data['state']),
            'description' => $data['description'] ?? null,
        ]);

        $this->mediaService->handleImageUploads(
            $profile,
            $images,
            $removeImageIds,
            $newMainImageIndex,
            $mainImageId,
        );

        if ($videoFile !== null) {
            $this->mediaService->handleVideoUpload($profile, $videoFile);
        }

        $this->mediaService->handleYouTubeVideo($profile, $videoUrl);

        return $profile->fresh();
    }

    /**
     * Get a profile with eager loads for public view, looking up by slug or ID.
     */
    public function getForPublicView(string $slug): Profile
    {
        $profile = Profile::withEagerLoads()->active()
            ->whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [Str::lower($slug)])
            ->orWhere('id', is_numeric($slug) ? (int) $slug : 0)
            ->firstOrFail();

        $profile->increment('views');

        return $profile;
    }

    /**
     * Get similar profiles from the same city.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Profile>
     */
    public function getSimilarProfiles(Profile $profile, int $limit = 4)
    {
        return Profile::with(['images'])
            ->similarTo($profile, $limit)
            ->get();
    }
}
