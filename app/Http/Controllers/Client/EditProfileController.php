<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EditProfileController extends Controller
{
    /**
     * Display the user's edit profile form.
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
    public function update(
        // ProfileUpdateRequest $request): RedirectResponse
        Request $request
    ): RedirectResponse {
        // $request->user()->fill($request->validated());

        // if ($request->user()->isDirty('email')) {
        //     $request->user()->email_verified_at = null;
        // }

        // $request->user()->save();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'birthday' => 'nullable|date',
            'image' => 'image|max:2048|nullable',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('image')) {
            $get_file_image = $request->file('image');
            $get_image_name = $get_file_image->getClientOriginalName();
            $get_image_extension = $get_file_image->getClientOriginalExtension();
            $image_name = current(explode('.', $get_image_name));
            $imagePath = time() . "-" . $image_name . "." . $get_image_extension;
            $get_file_image->move('./client/pfp/', $imagePath);
        } else {
            $imagePath = null;
        }

        $user = $request->user();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->birthday = $validated['birthday'] ?? $user->birthday;
        $user->pfp_url = $imagePath ?? $user->pfp_url;
        $user->gender = $validated['gender'] ?? $user->gender;
        $user->bio = $validated['bio'] ?? $user->bio;
        $user->address = $validated['address'] ?? $user->address;
        $user->website = $validated['website'] ?? $user->website;

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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
