<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        // CORRECTED VIEW PATH
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // Define the available roles for the dropdown menu.
        $roles = ['Admin', 'Sales', 'Mechanic'];

        // CORRECTED VIEW PATH
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user's role in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['Admin', 'Sales', 'Mechanic'])],
        ]);

        $user->role = $validated['role'];
        $user->save();

        // Use the correct route name, which does not have the 'admin.' prefix
        return redirect()->route('users.index')->with('success', 'User role updated successfully.');
    }
}
