<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\PersonalDataSheets;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
    
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        try {
            if($user->role === 'admin') {
                return redirect()->route('users.index')->with('error', 'Admin users cannot be deleted.');
            }
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Failed to delete user. A PDS record is associated with this user.');
        }
    }

    public function enable(User $user)
    {
        $user->update(['status' => 'enabled']);
        return redirect()->route('users.index')->with('success', 'User enabled successfully.');
    }

    public function disable(User $user)
    {
        $user->update(['status' => 'disabled']);
        return redirect()->route('users.index')->with('success', 'User disabled successfully.');
    }

    public function show(User $user)
    {
        $roles = Role::all();
        return view('users.show', compact('user', 'roles'));
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,hr_manager,employee','finance',
            'confirm' => 'accepted',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('users.show', $user)
            ->with('success', 'User role updated successfully!');
    }
}
