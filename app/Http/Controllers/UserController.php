<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /** @return list<string> */
    private static function allowedRoles(): array
    {
        return ['admin', 'manager', 'member', 'user'];
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        $users = User::query()->orderBy('name')->orderBy('id')->get();

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in(self::allowedRoles())],
        ]);

        $user = User::create($validated);

        if ($request->wantsJson()) {
            return response()->json($user, 201);
        }

        return redirect()->route('dashboard')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(self::allowedRoles())],
        ]);

        $user->update($validated);

        if ($request->wantsJson()) {
            return response()->json($user->fresh());
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $user->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
