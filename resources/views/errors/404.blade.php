<!-- resources/views/errors/404.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1">404</h1>
    <h2 class="mb-4">Page Not Found</h2>
    <p class="lead">The page you're looking for doesn't exist or has been moved.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Return Home</a>
</div>