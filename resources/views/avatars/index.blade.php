<!-- resources/views/avatars/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Your Balance</h5>
                    <h2>{{ auth()->user()->coins }} coins</h2>
                    <form action="{{ route('coins.topup') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary">Top Up (+100 coins)</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profile Visibility</h5>
                    <p>Current status: {{ auth()->user()->is_visible ? 'Visible' : 'Hidden' }}</p>
                    <form action="{{ route('profile.toggle-visibility') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ auth()->user()->is_visible ? 'warning' : 'success' }}">
                            {{ auth()->user()->is_visible ? 'Hide Profile (50 coins)' : 'Show Profile (5 coins)' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Available Avatars
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($avatars as $avatar)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="{{ $avatar->image_path }}" class="card-img-top" alt="Avatar">
                            <div class="card-body">
                                <h5 class="card-title">{{ $avatar->name }}</h5>
                                <p class="card-text">{{ $avatar->price }} coins</p>
                                <form action="{{ route('avatars.purchase', $avatar) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" 
                                            {{ auth()->user()->coins < $avatar->price ? 'disabled' : '' }}>
                                        Purchase
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            Your Avatar Collection
        </div>
        <div class="card-body">
            <div class="row">
                @foreach(auth()->user()->avatarGifts as $gift)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="{{ $gift->avatar->image_path }}" class="card-img-top" alt="Avatar">
                            <div class="card-body">
                                <p class="card-text">
                                    From: {{ $gift->sender->name }}<br>
                                    Received: {{ $gift->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>