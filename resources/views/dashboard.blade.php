@extends('layouts.app')

@section('title', __('Dashboard'))
@section('page_key', 'dashboard')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-primary mb-4">{{ __('Dashboard') }}</h2>
        <p class="text-gray-600">{{ __("You're logged in!") }}</p>
    </div>
@endsection
