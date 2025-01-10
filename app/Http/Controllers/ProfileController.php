<?php
// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load('hobbies');
        $allHobbies = \App\Models\Hobby::all();
        return view('profile.show', compact('user', 'allHobbies'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        
        $userData = $request->validated();
        
        // Only update password if provided
        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        // Update user basic info
        $user->update($userData);

        // Update hobbies
        if ($request->has('hobbies')) {
            $user->hobbies()->sync($request->hobbies);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $user->update(['avatar' => $path]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function matches()
    {
        $matches = auth()->user()->givenThumbs()
            ->where('is_matched', true)
            ->with('toUser.hobbies')
            ->get()
            ->pluck('toUser');

        return view('profile.matches', compact('matches'));
    }

    public function stats()
    {
        $user = auth()->user();
        
        $stats = [
            'total_matches' => $user->givenThumbs()->where('is_matched', true)->count(),
            'total_messages' => $user->sentMessages()->count() + $user->receivedMessages()->count(),
            'total_avatars' => $user->avatarGifts()->count(),
            'coins_spent' => $user->payments()->sum('amount'),
        ];

        return view('profile.stats', compact('stats'));
    }
}