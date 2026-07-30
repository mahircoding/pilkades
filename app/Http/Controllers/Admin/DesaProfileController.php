<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateDesaProfileRequest;
use App\Services\DesaProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DesaProfileController extends Controller
{
    public function __construct(
        private readonly DesaProfileService $service
    ) {}

    public function edit(): View
    {
        return view('admin.desa-profile.edit', [
            'profile' => $this->service->getProfile(),
        ]);
    }

    public function update(UpdateDesaProfileRequest $request): RedirectResponse
    {
        $this->service->updateProfile($request->validated());

        return redirect()->route('admin.desa-profile.edit')
            ->with('status', 'Profil desa berhasil diperbarui.');
    }
}
