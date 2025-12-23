@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Edit UKM: {{ $ukm->name }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.ukms.update', $ukm) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">UKM Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $ukm->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $ukm->email) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $ukm->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="logo" class="block text-gray-700 font-bold mb-2">Logo</label>
                    @if ($ukm->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $ukm->logo) }}" alt="{{ $ukm->name }}"
                                class="h-20 w-20 rounded-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-gray-500 text-sm mt-1">Leave blank to keep current logo. Max size: 2MB</p>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-bold mb-2">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $ukm->address) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $ukm->phone) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="website" class="block text-gray-700 font-bold mb-2">Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $ukm->website) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="established_date" class="block text-gray-700 font-bold mb-2">Established Date</label>
                    <input type="date" name="established_date" id="established_date"
                        value="{{ old('established_date', $ukm->established_date ? $ukm->established_date->format('Y-m-d') : '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="verification_status" class="block text-gray-700 font-bold mb-2">Status</label>
                    <select name="verification_status" id="verification_status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ $ukm->verification_status === 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive" {{ $ukm->verification_status === 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('admin.ukms.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        Update UKM
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
