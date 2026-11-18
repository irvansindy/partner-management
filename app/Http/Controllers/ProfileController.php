<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{DB, Storage};

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('users.profile', [
            'user' => $request->user()->loadMissing(['roles', 'office', 'division', 'dept', 'jobTitle']),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed', 'different:current_password'],
        ], [], [
            'current_password' => __('profile.current_password'),
            'password' => __('user_password.password'),
        ]);

        $user = $request->user();
        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();
        $request->session()->regenerate();
        $request->session()->put('password_hash_web', $user->password);

        return redirect()->route('profile')->with('password_updated', __('profile.password_success'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validateWithBag('photoUpdate', [
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=2048,max_height=2048'],
        ], [], ['avatar' => __('profile.photo')]);

        $user = $request->user();
        $disk = Storage::disk('local');
        $path = $request->file('avatar')->store('profile-photos/'.$user->id, 'local');
        abort_unless($path, 500);
        try {
            $oldPath = DB::transaction(function () use ($user, $path) {
                $lockedUser = $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
                $oldPath = $lockedUser->avatar_path;
                $lockedUser->forceFill(['avatar_path' => $path])->save();
                return $oldPath;
            });
        } catch (\Throwable $e) {
            $disk->delete($path);
            throw $e;
        }
        $user->forceFill(['avatar_path' => $path])->syncOriginalAttribute('avatar_path');
        if ($oldPath && preg_match('~^profile-photos/'.$user->id.'/[a-zA-Z0-9]+\.(?:jpg|jpeg|png|webp)$~', $oldPath)) {
            $disk->delete($oldPath);
        }

        return redirect()->route('profile')->with('photo_updated', __('profile.photo_success'));
    }

    public function photo(Request $request)
    {
        $path = $request->user()->avatar_path;
        abort_unless($path && preg_match('~^profile-photos/'.$request->user()->id.'/[a-zA-Z0-9]+\.(?:jpg|jpeg|png|webp)$~', $path), 404);
        abort_unless(Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path), [
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
