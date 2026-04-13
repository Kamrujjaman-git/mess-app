@extends('layouts.app')

@section('title', 'Add User — Mess App')

@section('content')
    <div class="mx-auto max-w-lg">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Add user</h1>
        <p class="mt-1 text-sm text-slate-500">Create a new member record.</p>

        <form action="{{ route('users.store') }}" method="post" class="form-card" novalidate>
            @csrf
            <div>
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name"
                       class="form-control @error('name') form-control-invalid @enderror">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email"
                       class="form-control @error('email') form-control-invalid @enderror">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" required
                        class="form-control @error('role') form-control-invalid @enderror">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choose role…</option>
                    @foreach (['admin' => 'Admin', 'manager' => 'Manager', 'member' => 'Member', 'user' => 'User'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="form-btn-primary">Create user</button>
                <a href="{{ route('users.index') }}" class="form-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
