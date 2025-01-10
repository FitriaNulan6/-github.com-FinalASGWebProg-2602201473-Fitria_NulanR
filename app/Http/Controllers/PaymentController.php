<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $payment = auth()->user()->payments()
            ->where('type', 'registration')
            ->where('is_completed', false)
            ->firstOrFail();

        return view('payments.show', compact('payment'));
    }

    public function process(Request $request)
    {
        $payment = auth()->user()->payments()
            ->where('type', 'registration')
            ->where('is_completed', false)
            ->firstOrFail();

        $amountPaid = $request->input('amount');
        $difference = $amountPaid - $payment->amount;

        if ($difference < 0) {
            return back()->with('error', "You are still underpaid " . abs($difference));
        }

        if ($difference > 0) {
            return view('payments.overpaid', [
                'payment' => $payment,
                'overpaidAmount' => $difference
            ]);
        }

        $payment->update(['is_completed' => true]);
        return redirect()->route('home')->with('success', 'Payment completed successfully!');
    }

    public function handleOverpaid(Request $request)
    {
        $payment = auth()->user()->payments()
            ->where('type', 'registration')
            ->where('is_completed', false)
            ->firstOrFail();

        $overpaidAmount = $request->input('overpaid_amount');
        
        if ($request->input('add_to_balance') === 'yes') {
            $coins = floor($overpaidAmount / 1000); // Convert to coins (1000 IDR = 1 coin)
            auth()->user()->increment('coins', $coins);
            $payment->update(['is_completed' => true]);
            return redirect()->route('home')->with('success', 'Payment completed and coins added to balance!');
        }

        return redirect()->route('payments.show');
    }
}