<!-- resources/views/errors/403.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1">403</h1>
    <h2 class="mb-4">Access Denied</h2>
    <p class="lead">Sorry, you don't have permission to access this page.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Return Home</a>
</div>



