<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->with('client')->latest()->paginate(10);

        return view('cms.users.index', compact('users'));
    }

    public function create(): View
    {
        $clients = Client::query()->orderBy('name')->get();

        return view('cms.users.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'client_id' => ['nullable', 'exists:clients,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::query()->create($validated);

        return redirect()->route('cms.users.index')
            ->with('success', 'User berhasil ditambah. Kosongkan client = admin.');
    }
}
