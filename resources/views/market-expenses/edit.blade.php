@extends('layouts.app')

@section('title', 'Edit Expense — Mess App')

@section('content')
    <div class="mx-auto max-w-lg">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit market expense</h1>
        <p class="mt-1 text-sm text-slate-500">Update this expense record.</p>

        @if ($users->isEmpty())
            <div class="form-callout">
                Add at least one user before editing expenses.
                <a href="{{ route('users.create') }}" class="font-semibold text-amber-900 underline decoration-amber-900/30 underline-offset-2 transition-colors duration-200 hover:text-amber-950">Create a user</a>
            </div>
        @endif

        <form action="{{ route('expenses.update', $expense) }}" method="post" class="form-card" novalidate>
            @csrf
            @method('PUT')
            <div>
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" required @disabled($users->isEmpty())
                        class="form-control @error('user_id') form-control-invalid @enderror">
                    <option value="" disabled {{ old('user_id', $expense->user_id) ? '' : 'selected' }}>Select user…</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected((string) old('user_id', $expense->user_id) === (string) $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0" required inputmode="decimal"
                       class="form-control tabular-nums @error('amount') form-control-invalid @enderror">
                @error('amount')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" required
                       class="form-control @error('date') form-control-invalid @enderror">
                @error('date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="note" class="form-label">Note <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea name="note" id="note" rows="3"
                          class="form-control @error('note') form-control-invalid @enderror">{{ old('note', $expense->note) }}</textarea>
                @error('note')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" @disabled($users->isEmpty()) class="form-btn-primary">Save changes</button>
                <a href="{{ route('expenses.index') }}" class="form-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
