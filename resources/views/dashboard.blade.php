@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
<p class="mt-1 text-sm text-slate-500">Overview of mess spending and meals for <span class="font-medium text-slate-700">{{ $monthLabel }}</span>.</p>

<form method="get" action="{{ route('dashboard') }}" class="mt-6 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm ring-1 ring-slate-900/[0.04] sm:p-5">
    <div class="min-w-0 flex-1 sm:max-w-xs">
        <label for="dashboard-month" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Month</label>
        <input type="month" name="month" id="dashboard-month" value="{{ $month }}"
               class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition hover:border-slate-300 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <button type="submit" class="btn-filter">
        <svg class="btn-filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        Filter
    </button>
</form>

<div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
    {{-- Total Expense --}}
    <article class="group stat-card">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Total expense</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">
                    {{ number_format($totalExpense, 2) }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Sum of market expenses</p>
            </div>
            <span class="stat-card-icon bg-indigo-50 text-indigo-600">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
    </article>

    {{-- Total Meals --}}
    <article class="group stat-card">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Total meals</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">
                    {{ number_format($totalMeals) }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Lunch + dinner units recorded</p>
            </div>
            <span class="stat-card-icon bg-emerald-50 text-emerald-600">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
    </article>

    {{-- Cost Per Meal --}}
    <article class="group stat-card">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Cost per meal</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">
                    @if ($costPerMeal !== null)
                        {{ number_format($costPerMeal, 2) }}
                    @else
                        <span class="text-slate-400">—</span>
                    @endif
                </p>
                <p class="mt-1 text-xs text-slate-400">Expense ÷ meal units</p>
            </div>
            <span class="stat-card-icon bg-amber-50 text-amber-600">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
    </article>
</div>

<section class="mt-10" aria-labelledby="dashboard-member-heading">
    <h2 id="dashboard-member-heading" class="text-lg font-semibold tracking-tight text-slate-900">Member balances</h2>
    <p class="mt-1 text-sm text-slate-500">
        Totals for <span class="font-medium text-slate-700">{{ $monthLabel }}</span>.
        <span class="text-emerald-700">Green balance</span> means a refund or credit; <span class="text-red-600">red</span> means amount still owed.
    </p>

    @if ($perUserBalances->isEmpty())
        <div class="empty-panel mt-6">
            No members to show yet.
        </div>
    @else
        <div class="table-surface mt-6">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-left text-sm text-slate-700">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/95">
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-xs font-semibold uppercase tracking-wider text-slate-500">User Name</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Total Meals</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Advance Paid</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Meal Cost</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($perUserBalances as $row)
                            @php
                                $bal = (float) $row['balance'];
                            @endphp
                            <tr class="table-row-interactive">
                                <td class="px-6 py-5 font-medium text-slate-900">{{ $row['name'] }}</td>
                                <td class="px-6 py-5 text-right tabular-nums text-slate-600">{{ number_format($row['meals']) }}</td>
                                <td class="px-6 py-5 text-right tabular-nums text-slate-900">{{ number_format($row['advancePaid'], 2) }}</td>
                                <td class="px-6 py-5 text-right tabular-nums text-slate-900">{{ number_format($row['cost'], 2) }}</td>
                                <td class="px-6 py-5 text-right align-top">
                                    @if ($bal > 0)
                                        <div class="ml-auto inline-block rounded-xl bg-emerald-50 px-3 py-2 text-right ring-1 ring-emerald-200/70">
                                            <div class="tabular-nums text-base font-semibold text-emerald-800">{{ number_format($row['balance'], 2) }}</div>
                                            <div class="mt-0.5 text-[0.7rem] font-medium uppercase tracking-wide text-emerald-700">Will get money</div>
                                        </div>
                                    @elseif ($bal < 0)
                                        <div class="ml-auto inline-block rounded-xl bg-red-50 px-3 py-2 text-right ring-1 ring-red-200/70">
                                            <div class="tabular-nums text-base font-semibold text-red-800">{{ number_format($row['balance'], 2) }}</div>
                                            <div class="mt-0.5 text-[0.7rem] font-medium uppercase tracking-wide text-red-700">Needs to pay</div>
                                        </div>
                                    @else
                                        <span class="tabular-nums font-medium text-slate-600">{{ number_format($row['balance'], 2) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</section>
@endsection
