<!-- resources/views/payments/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registration Payment') }}</div>

                <div class="card-body">
                    <h4>Amount to Pay: Rp {{ number_format($payment->amount, 0, ',', '.') }}</h4>
                    
                    <form method="POST" action="{{ route('payments.process') }}">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Enter Payment Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Process Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

