@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Create New User</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                        required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-gray-700 font-bold mb-2">Role</label>
                    <select name="role" id="role"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="ukm" {{ old('role') === 'ukm' ? 'selected' : '' }}>UKM</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="ukm_id" class="block text-gray-700 font-bold mb-2">UKM (Optional)</label>
                    <select name="ukm_id" id="ukm_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">None</option>
                        @foreach ($ukms as $ukm)
                            <option value="{{ $ukm->id }}" {{ old('ukm_id') == $ukm->id ? 'selected' : '' }}>
                                {{ $ukm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
