<!-- resources/views/errors/500.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1">500</h1>
    <h2 class="mb-4">Server Error</h2>
    <p class="lead">Something went wrong on our end. Please try again later.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Return Home</a>
</div>