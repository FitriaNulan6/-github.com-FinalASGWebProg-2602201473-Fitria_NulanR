// app/Http/Controllers/CoinController.php
namespace App\Http\Controllers;

class CoinController extends Controller
{
    public function topup()
    {
        auth()->user()->increment('coins', 100);
        return back()->with('success', '100 coins added to your balance!');
    }

    public function toggleVisibility()
    {
        $user = auth()->user();
        
        if ($user->is_visible) {
            if ($user->coins < 50) {
                return back()->with('error', 'Insufficient coins! Need 50 coins to hide profile.');
            }
            
            $user->decrement('coins', 50);
            $user->update([
                'is_visible' => false,
                'avatar' => '/images/bears/' . rand(1, 3) . '.png'
            ]);
            
            return back()->with('success', 'Profile hidden successfully!');
        } else {
            if ($user->coins < 5) {
                return back()->with('error', 'Insufficient coins! Need 5 coins to show profile.');
            }
            
            $user->decrement('coins', 5);
            $user->update([
                'is_visible' => true,
                'avatar' => null
            ]);
            
            return back()->with('success', 'Profile visible again!');
        }
    }
}