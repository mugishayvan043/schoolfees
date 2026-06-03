<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        Log::info('User created by admin.', ['created_user_id' => $user->id, 'admin_id' => auth()->id()]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('users.edit', $id);
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $data = $this->validated($request, $user->id);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        Log::info('User updated by admin.', ['updated_user_id' => $user->id, 'admin_id' => auth()->id()]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();
        Log::warning('User deleted by admin.', ['deleted_user_id' => $user->id, 'admin_id' => auth()->id()]);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    private function validated(Request $request, ?int $userId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users')->ignore($userId)],
            'role' => ['required', 'in:admin,accountant'],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:5', 'confirmed'],
        ]);
    }
}
