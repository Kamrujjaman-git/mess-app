@extends('layouts.app')

@section('title', 'Monthly summary — Mess App')

@section('content')
<article class="summary-report" aria-labelledby="summary-page-title">
    {{-- Top accent --}}
    <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-indigo-600" aria-hidden="true"></div>

    {{-- Header --}}
    <header class="relative border-b border-slate-200/80 bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 px-6 py-8 sm:px-10 sm:py-10">
        <div class="pointer-events-none absolute -right-20 -top-24 size-[28rem] rounded-full bg-indigo-400/[0.07] blur-3xl" aria-hidden="true"></div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0 max-w-2xl">
                <p class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-indigo-600">Financial report</p>
                <h1 id="summary-page-title" class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Monthly summary
                </h1>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Consolidated market spend, meal units, per-meal rate, and member settlement after rent and maid for the selected calendar month.
                </p>
            </div>
            <div class="flex shrink-0 flex-col items-stretch gap-3 sm:flex-row sm:items-center lg:flex-col lg:items-end">
                <div class="inline-flex items-center gap-2 rounded-2xl border border-indigo-200/80 bg-white/90 px-4 py-2.5 text-sm font-semibold text-indigo-950 shadow-sm ring-1 ring-indigo-100/80 backdrop-blur-sm">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-inner shadow-indigo-900/20" aria-hidden="true">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <span class="text-left leading-tight">
                        <span class="block text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500">Reporting period</span>
                        <span class="tabular-nums text-base text-slate-900">{{ $monthLabel }}</span>
                    </span>
                </div>
                <span class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-medium tabular-nums text-slate-600 shadow-sm ring-1 ring-slate-900/[0.03] sm:min-w-[8.5rem]">
                    {{ $month }}
                </span>
            </div>
        </div>
    </header>

    {{-- Period control --}}
    <div class="border-b border-slate-200/80 bg-slate-50/40 px-6 py-5 sm:px-10">
        <form method="get"
              action="{{ route('monthly-summary.index') }}"
              class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
            <div class="min-w-0 sm:max-w-xs sm:flex-1">
                <label for="summary-month" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Change month
                </label>
                <input type="month"
                       name="month"
                       id="summary-month"
                       value="{{ request('month', date('Y-m')) }}"
                       class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-900 shadow-sm transition hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <button type="submit" class="btn-filter w-full sm:w-auto">
                <svg class="btn-filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Update report
            </button>
        </form>
    </div>

    {{-- Merged KPI strip --}}
    <div class="summary-kpi-grid border-b border-slate-200/80" role="region" aria-label="Period totals">
        <div class="group summary-kpi-cell">
            <span class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-indigo-500 to-indigo-400" aria-hidden="true"></span>
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total expense</p>
                    <p class="mt-3 text-3xl font-bold tabular-nums tracking-tight text-slate-900 sm:text-4xl">
                        {{ number_format($totalExpense, 2) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">All market purchases in this month</p>
                </div>
                <span class="stat-card-icon shrink-0 bg-indigo-50 text-indigo-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
        </div>
        <div class="group summary-kpi-cell">
            <span class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-emerald-500 to-teal-400" aria-hidden="true"></span>
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total meals</p>
                    <p class="mt-3 text-3xl font-bold tabular-nums tracking-tight text-slate-900 sm:text-4xl">
                        {{ number_format($totalMeals) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">Lunch and dinner units counted</p>
                </div>
                <span class="stat-card-icon shrink-0 bg-emerald-50 text-emerald-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
        </div>
        <div class="group summary-kpi-cell">
            <span class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-amber-500 to-orange-400" aria-hidden="true"></span>
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Cost per meal</p>
                    <p class="mt-3 text-3xl font-bold tabular-nums tracking-tight text-slate-900 sm:text-4xl">
                        @if ($costPerMeal !== null)
                            {{ number_format($costPerMeal, 2) }}
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </p>
                    <p class="mt-2 text-xs text-slate-500">Expense divided by meal units</p>
                </div>
                <span class="stat-card-icon shrink-0 bg-amber-50 text-amber-600">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    {{-- Member table --}}
    <section class="px-6 py-8 sm:px-10 sm:py-10" aria-labelledby="summary-members-heading">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 id="summary-members-heading" class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">
                    Member settlement
                </h2>
                <p id="summary-members-desc" class="mt-1 max-w-2xl text-sm text-slate-500">
                    Per-person meals, advances, allocated meal cost, fixed charges, and net position for
                    <span class="font-medium text-slate-700">{{ $monthLabel }}</span>.
                    <span class="text-emerald-700">Green</span> indicates a credit; <span class="text-red-600">red</span> indicates money owed.
                </p>
            </div>
        </div>

        @if ($usersData->isEmpty())
            <div class="empty-panel mt-8 flex flex-col items-center gap-3 text-center">
                <span class="flex size-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 ring-1 ring-slate-200/80" aria-hidden="true">
                    <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
                <p>No members to show yet.</p>
            </div>
        @else
            <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-md shadow-slate-200/30 ring-1 ring-slate-900/[0.03]">
                <div class="overflow-x-auto">
                    <table class="min-w-[760px] border-collapse text-left text-sm md:min-w-full"
                           aria-describedby="summary-members-desc">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-100/90">
                                <th scope="col" class="sticky left-0 z-10 whitespace-nowrap bg-slate-100/90 px-4 py-4 text-xs font-semibold uppercase tracking-wider text-slate-600 shadow-[6px_0_12px_-8px_rgba(15,23,42,0.25)] md:static md:bg-transparent md:px-6 md:py-4 md:shadow-none">Name</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 md:px-6">Meals</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 md:px-6">Advance</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 md:px-6">Meal cost</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 md:px-6">Meal balance</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 md:px-6">Rent</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 md:px-6">Maid</th>
                                <th scope="col" class="whitespace-nowrap px-4 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-600 md:px-6">Final balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($usersData as $row)
                                @php
                                    $mealBal = (float) $row['mealBalance'];
                                    $finalBal = (float) $row['finalBalance'];
                                @endphp
                                <tr class="group table-row-interactive">
                                    <th scope="row" class="sticky left-0 z-10 whitespace-nowrap bg-white px-4 py-4 text-left text-sm font-semibold text-slate-900 shadow-[6px_0_12px_-8px_rgba(15,23,42,0.12)] group-hover:bg-indigo-50/55 md:static md:bg-transparent md:px-6 md:py-5 md:shadow-none md:group-hover:bg-transparent">
                                        {{ $row['name'] }}
                                    </th>
                                    <td class="px-4 py-4 text-right tabular-nums text-slate-600 md:px-6 md:py-5">{{ number_format($row['totalMeals']) }}</td>
                                    <td class="px-4 py-4 text-right tabular-nums text-slate-900 md:px-6 md:py-5">{{ number_format($row['advancePaid'], 2) }}</td>
                                    <td class="px-4 py-4 text-right tabular-nums text-slate-900 md:px-6 md:py-5">{{ number_format($row['mealCost'], 2) }}</td>
                                    <td class="px-4 py-4 text-right align-middle md:px-6 md:py-5">
                                        @if ($mealBal > 0)
                                            <span class="tabular-nums font-semibold text-emerald-700">{{ number_format($row['mealBalance'], 2) }}</span>
                                        @elseif ($mealBal < 0)
                                            <span class="tabular-nums font-semibold text-red-600">{{ number_format($row['mealBalance'], 2) }}</span>
                                        @else
                                            <span class="tabular-nums font-medium text-slate-500">{{ number_format($row['mealBalance'], 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right tabular-nums text-slate-700 md:px-6 md:py-5">{{ number_format($row['rent'], 2) }}</td>
                                    <td class="px-4 py-4 text-right tabular-nums text-slate-700 md:px-6 md:py-5">{{ number_format($row['maid'], 2) }}</td>
                                    <td class="px-4 py-4 text-right align-middle md:px-6 md:py-5">
                                        @if ($finalBal > 0)
                                            <div class="ml-auto inline-block max-w-[12rem] rounded-xl bg-emerald-50 px-3 py-2 text-right ring-1 ring-emerald-200/80">
                                                <div class="tabular-nums text-base font-semibold text-emerald-900">{{ number_format($row['finalBalance'], 2) }}</div>
                                                <div class="mt-0.5 text-[0.65rem] font-semibold uppercase tracking-wide text-emerald-700">Will get money</div>
                                            </div>
                                        @elseif ($finalBal < 0)
                                            <div class="ml-auto inline-block max-w-[12rem] rounded-xl bg-red-50 px-3 py-2 text-right ring-1 ring-red-200/80">
                                                <div class="tabular-nums text-base font-semibold text-red-900">{{ number_format($row['finalBalance'], 2) }}</div>
                                                <div class="mt-0.5 text-[0.65rem] font-semibold uppercase tracking-wide text-red-700">Needs to pay</div>
                                            </div>
                                        @else
                                            <span class="tabular-nums font-medium text-slate-600">{{ number_format($row['finalBalance'], 2) }}</span>
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
</article>
@endsection
