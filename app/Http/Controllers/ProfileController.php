<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        // Validasi password
        $request->validate([
            'password' => ['required'],
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'userDeletion.password' => __('The provided password is incorrect.'),
            ]);
        }

        // Hapus pengguna
        $user = $request->user();

        // Log out user
        Auth::logout();

        // Hapus akun pengguna
        $user->delete();

        // Redirect ke halaman awal
        return redirect('/')->with('success', __('Your account has been deleted.'));
    }
}
