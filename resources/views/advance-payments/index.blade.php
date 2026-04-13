@extends('layouts.app')

@section('title', 'Advance payments — Mess App')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Advance payments</h1>
            <p class="mt-1 text-sm text-slate-500">Member prepayments toward the mess.</p>
        </div>
        <a href="{{ route('advance-payments.create') }}" class="btn-cta">
            Add payment
        </a>
    </div>

    @if ($payments->isEmpty())
        <div class="empty-panel">
            No advance payments yet. <a href="{{ route('advance-payments.create') }}" class="link-inline">Record one</a>.
        </div>
    @else
        <div class="table-surface">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-left text-sm text-slate-700">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/95">
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-xs font-semibold uppercase tracking-wider text-slate-500">User</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($payments as $payment)
                            <tr class="table-row-interactive">
                                <td class="px-6 py-5 font-medium text-slate-900">{{ $payment->user->name }}</td>
                                <td class="px-6 py-5 text-right tabular-nums text-slate-900">{{ number_format((float) $payment->amount, 2) }}</td>
                                <td class="px-6 py-5 text-slate-600">{{ $payment->date->format('M j, Y') }}</td>
                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <a href="{{ route('advance-payments.edit', $payment) }}" class="action-link">Edit</a>
                                        <form action="{{ route('advance-payments.destroy', $payment) }}" method="post" class="inline"
                                              onsubmit="return confirm('Delete this advance payment? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-link-danger cursor-pointer">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
