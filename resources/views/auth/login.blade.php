@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-24 mb-28 p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Login Admin</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required autofocus>
        </div>
        <div class="mb-6">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                {{ $errors->first() }}
            </div>
        @endif
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
    </form>
</div>
@endsection
