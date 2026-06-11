<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Profile;
use App\Models\ProfilePhysicalAttribute;
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
     * @param  array<UploadedFile>  $videoFiles
     * @return Profile
     */
    public function create(User $user, array $data, array $images = [], array $videoFiles = []): Profile
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

        $this->syncServices($profile, $data['services'] ?? []);

        $this->syncPhysicalAttributes($profile, $data);

        if (!empty($images)) {
            $this->mediaService->handleImageUploads($profile, $images);
        }

        if (!empty($videoFiles)) {
            $this->mediaService->handleVideoUploads($profile, $videoFiles);
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
     * @param  array<UploadedFile>  $videoFiles
     * @return Profile
     */
    public function update(
        Profile $profile,
        array $data,
        array $images = [],
        array $removeImageIds = [],
        ?int $newMainImageIndex = null,
        ?int $mainImageId = null,
        array $videoFiles = [],
    ): Profile {
        $profile->update([
            'name'        => $data['name'],
            'age'         => $data['age'],
            'city'        => $data['city'],
            'state'       => strtoupper($data['state']),
            'description' => $data['description'] ?? null,
        ]);

        $this->syncServices($profile, $data['services'] ?? []);

        $this->syncPhysicalAttributes($profile, $data);

        $this->mediaService->handleImageUploads(
            $profile,
            $images,
            $removeImageIds,
            $newMainImageIndex,
            $mainImageId,
        );

        if (!empty($videoFiles)) {
            $this->mediaService->handleVideoUploads($profile, $videoFiles);
        }

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

    /**
     * Sync the services attached to a profile via the pivot table.
     */
    private function syncServices(Profile $profile, array $serviceIds): void
    {
        if (empty($serviceIds)) {
            $profile->services()->detach();
            return;
        }

        $syncData = [];
        foreach ($serviceIds as $id) {
            $syncData[$id] = [];
        }

        $profile->services()->sync($syncData);
    }

    /**
     * Sync the physical attributes for a profile.
     *
     * @param  Profile  $profile
     * @param  array<string, mixed>  $data
     */
    private function syncPhysicalAttributes(Profile $profile, array $data): void
    {
        $attributeFields = ['height', 'weight', 'hair_color', 'eye_color', 'ethnicity', 'body_type'];
        
        $hasAnyValue = false;
        $attributes = ['profile_id' => $profile->id];
        
        foreach ($attributeFields as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && $data[$field] !== null) {
                $attributes[$field] = $data[$field];
                $hasAnyValue = true;
            }
        }

        if (!$hasAnyValue) {
            // If profile has existing attributes but no new ones, delete them
            if ($profile->physicalAttributes) {
                $profile->physicalAttributes()->delete();
            }
            return;
        }

        if ($profile->physicalAttributes) {
            $profile->physicalAttributes()->update($attributes);
        } else {
            $profile->physicalAttributes()->create($attributes);
        }
    }
}
