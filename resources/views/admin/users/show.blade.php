@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">User Details</h1>
            <div>
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Name</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Phone</label>
                    <p class="text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Role</label>
                    <p class="text-gray-900">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full
                        @if ($user->role === 'admin') bg-red-100 text-red-800
                        @elseif($user->role === 'ukm') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">UKM</label>
                    <p class="text-gray-900">{{ $user->ukm ? $user->ukm->name : 'None' }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Created At</label>
                    <p class="text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            @if ($user->events->count() > 0)
                <div class="mt-6">
                    <h2 class="text-xl font-bold mb-4">Registered Events</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($user->events as $event)
                                    <tr>
                                        <td class="px-6 py-4">{{ $event->title }}</td>
                                        <td class="px-6 py-4">{{ $event->event_date }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $event->pivot->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
