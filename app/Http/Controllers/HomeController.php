<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hobby;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = User::where('id', '!=', auth()->id())
            ->where('is_visible', true);

        // Apply gender filter
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        // Apply hobby filter
        if ($request->has('hobby')) {
            $query->whereHas('hobbies', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->hobby . '%');
            });
        }

        $users = $query->with(['hobbies'])
            ->paginate(12);

        $hobbies = Hobby::orderBy('name')->get();

        return view('home', compact('users', 'hobbies'));
    }

    public function toggleThumb(User $user)
    {
        $thumb = auth()->user()->givenThumbs()
            ->where('to_user_id', $user->id)
            ->first();

        if ($thumb) {
            $thumb->delete();
            return response()->json(['status' => 'removed']);
        }

        $thumb = auth()->user()->givenThumbs()->create([
            'to_user_id' => $user->id
        ]);

        // Check if it's a match
        $isMatch = $user->givenThumbs()
            ->where('to_user_id', auth()->id())
            ->exists();

        if ($isMatch) {
            $thumb->update(['is_matched' => true]);
            // You could trigger a notification here
        }

        // Add to HomeController's toggleThumb method:
        if ($isMatch) {
            $thumb->update(['is_matched' => true]);
            $user->notify(new NewMatchNotification(auth()->user()));
            auth()->user()->notify(new NewMatchNotification($user));
        }

        return response()->json([
            'status' => 'added',
            'is_match' => $isMatch
        ]);
    }
}