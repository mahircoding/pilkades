<?php

namespace App\Services;

use App\Models\DesaProfile;

class DesaProfileService
{
    public function getProfile(): DesaProfile
    {
        return DesaProfile::firstOrCreate(
            [],
            ['name' => 'Desa Baru']
        );
    }

    public function updateProfile(array $data): DesaProfile
    {
        $profile = $this->getProfile();
        $profile->update($data);

        return $profile;
    }
}
