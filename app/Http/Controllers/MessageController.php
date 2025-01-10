<?php
// app/Http/Controllers/MessageController.php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $matches = auth()->user()->givenThumbs()
            ->where('is_matched', true)
            ->with('toUser')
            ->get()
            ->pluck('toUser');

        return view('messages.index', compact('matches'));
    }

    public function show(User $user)
    {
        // Verify if users are matched
        if (!auth()->user()->hasMatchWith($user)) {
            return redirect()->route('messages.index')
                ->with('error', 'You can only message users you have matched with.');
        }

        $messages = Message::where(function($query) use ($user) {
                $query->where('from_user_id', auth()->id())
                    ->where('to_user_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('from_user_id', $user->id)
                    ->where('to_user_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('to_user_id', auth()->id())
            ->where('from_user_id', $user->id)
            ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        if (!auth()->user()->hasMatchWith($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $user->id,
            'content' => $request->content
        ]);

        // Broadcast the new message
        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message);
    }
}