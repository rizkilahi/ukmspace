<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UKM;

class UserController extends Controller
{
    /**
     * Display a listing of all users (Admin).
     */
    public function index()
    {
        $users = User::with('ukm')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user (Admin).
     */
    public function create()
    {
        // Select only needed columns for dropdown
        $ukms = UKM::where('verification_status', 'active')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.users.create', compact('ukms'));
    }

    /**
     * Store a newly created user (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:15',
            'ukm_id' => 'nullable|exists:ukms,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);
        $user->role = $request->input('role', 'user');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user (Admin).
     */
    public function show(User $user)
    {
        $user->load('ukm', 'events');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user (Admin).
     */
    public function edit(User $user)
    {
        // Select only needed columns for dropdown
        $ukms = UKM::where('verification_status', 'active')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.users.edit', compact('user', 'ukms'));
    }

    /**
     * Update the specified user (Admin).
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'ukm_id' => 'nullable|exists:ukms,id',
        ]);

        // Update base fields
        $user->update($validated);

        // Only allow admin to change role (separate save for guarded field)
        if ($request->filled('role')) {
            $user->role = $request->input('role');
            $user->save();
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
            $user->save();
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user (Admin).
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
