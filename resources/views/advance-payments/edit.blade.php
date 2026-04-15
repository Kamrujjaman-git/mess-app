@extends('layouts.app')

@section('title', 'Edit advance payment — Mess App')

@section('content')
    <div class="mx-auto max-w-lg">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit advance payment</h1>
        <p class="mt-1 text-sm text-slate-500">Update this prepayment record.</p>

        @if ($users->isEmpty())
            <div class="form-callout">
                Add at least one user before saving.
                <a href="{{ route('users.create') }}" class="font-semibold text-amber-900 underline decoration-amber-900/30 underline-offset-2 transition-colors duration-200 hover:text-amber-950">Create a user</a>
            </div>
        @endif

        <form action="{{ route('advance-payments.update', $payment) }}" method="post" class="form-card" novalidate>
            @csrf
            @method('PUT')
            <div>
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" required @disabled($users->isEmpty())
                        class="form-control @error('user_id') form-control-invalid @enderror">
                    <option value="" selected>Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" step="0.01" required inputmode="decimal"
                       class="form-control tabular-nums @error('amount') form-control-invalid @enderror">
                @error('amount')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" value="{{ old('date', $payment->date->toDateString()) }}" required
                       class="form-control @error('date') form-control-invalid @enderror">
                @error('date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" @disabled($users->isEmpty()) class="form-btn-primary">Update payment</button>
                <a href="{{ route('advance-payments.index') }}" class="form-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
