<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all users, paginate the results, and pass them to the view.
        $users = User::paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Update the specified user's role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Validate that the role being submitted is one of our allowed roles.
        $validated = $request->validate([
            'role' => ['required', Rule::in(['Admin', 'Sales', 'Mechanic'])],
        ]);

        // Prevent an admin from accidentally removing their own admin role
        // if they are the only admin left in the system.
        if ($user->id === auth()->id() && $user->role === 'Admin' && User::where('role', 'Admin')->count() === 1) {
            return redirect()->back()->with('error', 'Cannot remove the last administrator role.');
        }

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User role updated successfully.');
    }
}
