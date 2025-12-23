@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">UKM Details: {{ $ukm->name }}</h1>
            <div>
                <a href="{{ route('admin.ukms.edit', $ukm) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                </a>
                <a href="{{ route('admin.ukms.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex items-start mb-6">
                @if ($ukm->logo)
                    <img src="{{ asset('storage/' . $ukm->logo) }}" alt="{{ $ukm->name }}"
                        class="h-24 w-24 rounded-full object-cover mr-6">
                @else
                    <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center mr-6">
                        <span class="text-gray-500 text-2xl font-bold">{{ substr($ukm->name, 0, 1) }}</span>
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">{{ $ukm->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ $ukm->description }}</p>
                    <span
                        class="px-3 py-1 text-sm font-semibold rounded-full
                    {{ $ukm->verification_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($ukm->verification_status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <p class="text-gray-900">{{ $ukm->email }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Phone</label>
                    <p class="text-gray-900">{{ $ukm->phone ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Address</label>
                    <p class="text-gray-900">{{ $ukm->address ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Website</label>
                    <p class="text-gray-900">
                        @if ($ukm->website)
                            <a href="{{ $ukm->website }}" target="_blank"
                                class="text-blue-600 hover:underline">{{ $ukm->website }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Established Date</label>
                    <p class="text-gray-900">
                        {{ $ukm->established_date ? $ukm->established_date->format('M d, Y') : 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Created At</label>
                    <p class="text-gray-900">{{ $ukm->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        @if ($ukm->events->count() > 0)
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Events ({{ $ukm->events->count() }})</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($ukm->events as $event)
                                <tr>
                                    <td class="px-6 py-4">{{ $event->title }}</td>
                                    <td class="px-6 py-4">{{ $event->event_date }}</td>
                                    <td class="px-6 py-4">{{ $event->location }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($ukm->users->count() > 0)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Members ({{ $ukm->users->count() }})</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($ukm->users as $user)
                                <tr>
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
