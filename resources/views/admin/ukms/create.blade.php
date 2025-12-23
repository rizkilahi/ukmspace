@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Create New UKM</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.ukms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">UKM Name</label>
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
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="logo" class="block text-gray-700 font-bold mb-2">Logo</label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-gray-500 text-sm mt-1">Max size: 2MB</p>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-bold mb-2">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="website" class="block text-gray-700 font-bold mb-2">Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="established_date" class="block text-gray-700 font-bold mb-2">Established Date</label>
                    <input type="date" name="established_date" id="established_date"
                        value="{{ old('established_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="verification_status" class="block text-gray-700 font-bold mb-2">Status</label>
                    <select name="verification_status" id="verification_status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ old('verification_status') === 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive" {{ old('verification_status') === 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('admin.ukms.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        Create UKM
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
