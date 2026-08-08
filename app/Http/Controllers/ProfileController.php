<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\AuthService;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly ImageOptimizer $imageOptimizer,
    ) {
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $this->authorize('update', $user);

        $data = $request->safe()->except('avatar');

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            // Stored under a per-user prefix; filename is server-generated
            // (never derived from client input) to prevent path traversal.
            $data['avatar_path'] = $this->imageOptimizer->storeOptimized($request->file('avatar'), 'avatars/' . $user->id, 'public');
        }

        if ($user->email !== $data['email']) {
            $data['email_verified_at'] = null;
        }

        $user->fill($data)->save();

        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $this->authService->changePassword($request->user(), $request->validated()['password']);

        return back()->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $user = $request->user();
        $this->authorize('deleteOwnAccount', $user);

        $this->authService->logout();
        $this->authService->deleteAccount($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
