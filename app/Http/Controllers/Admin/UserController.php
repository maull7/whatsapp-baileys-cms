<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with('client')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $clients = Client::query()->orderBy('name')->get();

        return view('admin.users.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,client',
            'client_id' => 'nullable|exists:clients,id|required_if:role,client',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($validated['role'] === 'admin') {
            $validated['client_id'] = null;
        }

        User::query()->create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user): View
    {
        $user->load('client');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $clients = Client::query()->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'clients'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,client',
            'client_id' => 'nullable|exists:clients,id|required_if:role,client',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($validated['role'] === 'admin') {
            $validated['client_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function regenerateApiKey(User $user): RedirectResponse
    {
        if (! $user->isClient()) {
            return redirect()->back()
                ->with('error', 'Hanya user client yang memiliki API key.');
        }

        $user->update(['api_key' => 'wa_' . bin2hex(random_bytes(28))]);

        return redirect()->back()
            ->with('success', 'API key berhasil di-generate ulang.');
    }
}
