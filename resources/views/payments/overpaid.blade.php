<!-- resources/views/payments/overpaid.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Overpayment Detected') }}</div>

                <div class="card-body">
                    <h4>You overpaid: Rp {{ number_format($overpaidAmount, 0, ',', '.') }}</h4>
                    <p>Would you like to add this to your balance as coins?</p>
                    
                    <form method="POST" action="{{ route('payments.handle-overpaid') }}">
                        @csrf
                        <input type="hidden" name="overpaid_amount" value="{{ $overpaidAmount }}">
                        <button type="submit" name="add_to_balance" value="yes" class="btn btn-primary">
                            Yes, add to my balance
                        </button>
                        <button type="submit" name="add_to_balance" value="no" class="btn btn-secondary">
                            No, let me pay again
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>