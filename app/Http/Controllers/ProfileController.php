<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('profile-photos', 'public');
            $validated['foto_profil'] = $photoPath;
        }

        // Map form fields to database fields (exclude email since it's not editable)
        $userData = [
            'nama_depan' => $validated['first_name'] ?? $user->nama_depan,
            'nama_belakang' => $validated['last_name'] ?? $user->nama_belakang, 
            'no_whatsapp' => $validated['whatsapp'] ?? $user->no_whatsapp,
            'tanggal_lahir' => $validated['birth_date'] ?? $user->tanggal_lahir,
            'jenis_kelamin' => $validated['gender'] ?? $user->jenis_kelamin,
        ];

        // Add photo path if uploaded
        if (isset($validated['foto_profil'])) {
            $userData['foto_profil'] = $validated['foto_profil'];
        }

        // Handle password update
        if (!empty($validated['password'])) {
            $userData['password'] = bcrypt($validated['password']);
        }

        // Fill and save user data
        $user->fill($userData);

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile photo if exists
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}