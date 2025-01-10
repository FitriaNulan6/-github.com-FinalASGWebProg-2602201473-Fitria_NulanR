<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('home') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">All</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hobby</label>
                    <select name="hobby" class="form-select">
                        <option value="">All</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->name }}" {{ request('hobby') == $hobby->name ? 'selected' : '' }}>
                                {{ $hobby->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Grid -->
    <div class="row">
        @foreach($users as $user)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $user->avatar ?? '/images/default-avatar.png' }}" class="card-img-top" alt="{{ $user->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <p class="card-text">
                            <strong>Hobbies:</strong>
                            {{ $user->hobbies->pluck('name')->implode(', ') }}
                        </p>
                        <p class="card-text">
                            <a href="{{ $user->instagram }}" target="_blank">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                        </p>
                        <button 
                            class="btn btn-outline-primary thumb-button" 
                            data-user-id="{{ $user->id }}"
                            data-thumbed="{{ auth()->user()->givenThumbs()->where('to_user_id', $user->id)->exists() ? 'true' : 'false' }}"
                        >
                            <i class="fas fa-thumbs-up"></i>
                            <span>{{ auth()->user()->givenThumbs()->where('to_user_id', $user->id)->exists() ? 'Thumbed' : 'Thumb' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $users->links() }}
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.thumb-button').click(function() {
        const button = $(this);
        const userId = button.data('user-id');
        
        $.post(`/users/${userId}/thumb`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.status === 'added') {
                button.data('thumbed', 'true');
                button.find('span').text('Thumbed');
                if (response.is_match) {
                    alert('It\'s a match! You can now chat with this user.');
                }
            } else {
                button.data('thumbed', 'false');
                button.find('span').text('Thumb');
            }
        });
    });
});
</script>
@endpush