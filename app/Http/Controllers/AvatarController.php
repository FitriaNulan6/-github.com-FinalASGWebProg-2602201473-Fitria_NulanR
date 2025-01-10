<?php
// app/Http/Controllers/AvatarController.php
namespace App\Http\Controllers;

use App\Models\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function index()
    {
        $avatars = Avatar::all();
        $userCoins = auth()->user()->coins;
        
        return view('avatars.index', compact('avatars', 'userCoins'));
    }

    public function purchase(Avatar $avatar)
    {
        $user = auth()->user();

        if ($user->coins < $avatar->price) {
            return back()->with('error', 'Insufficient coins!');
        }

        $user->decrement('coins', $avatar->price);
        $user->update(['avatar' => $avatar->image_path]);

        return back()->with('success', 'Avatar purchased successfully!');
    }

    public function send(Request $request, User $recipient)
    {
        $request->validate([
            'avatar_id' => 'required|exists:avatars,id'
        ]);

        $avatar = Avatar::findOrFail($request->avatar_id);
        $sender = auth()->user();

        if ($sender->coins < $avatar->price) {
            return back()->with('error', 'Insufficient coins!');
        }

        $sender->decrement('coins', $avatar->price);
        $recipient->increment('coins', floor($avatar->price * 0.5)); // Recipient gets 50% of avatar value

        // Record the gift
        AvatarGift::create([
            'from_user_id' => $sender->id,
            'to_user_id' => $recipient->id,
            'avatar_id' => $avatar->id
        ]);

        return back()->with('success', 'Avatar sent successfully!');
    }
}

