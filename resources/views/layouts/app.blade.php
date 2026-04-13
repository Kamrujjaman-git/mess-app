<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mess App')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-full flex-col bg-slate-50 font-sans text-slate-900 antialiased">
    {{-- Mobile drawer toggle (peer target) --}}
    <input type="checkbox" id="sidebar-drawer" class="peer/sidebar sr-only" autocomplete="off">

    <div class="flex min-h-0 flex-1 flex-col lg:min-h-screen">
        <div class="flex min-h-0 flex-1">
            {{-- Backdrop: closes drawer when tapped --}}
            <label for="sidebar-drawer" aria-hidden="true"
                   class="fixed inset-0 z-30 bg-slate-900/50 opacity-0 pointer-events-none transition-opacity duration-200 peer-checked/sidebar:pointer-events-auto peer-checked/sidebar:opacity-100 lg:hidden"></label>

            {{-- Sidebar --}}
            <aside id="app-sidebar"
                   class="fixed inset-y-0 left-0 z-40 flex w-64 shrink-0 -translate-x-full flex-col border-r border-slate-200/80 bg-white shadow-xl transition-transform duration-200 ease-out peer-checked/sidebar:translate-x-0 lg:static lg:z-0 lg:translate-x-0 lg:shadow-none">
                <div class="flex h-14 items-center border-b border-slate-100 px-4 lg:hidden">
                    <span class="text-sm font-semibold tracking-tight text-slate-800">Menu</span>
                    <label for="sidebar-drawer" class="ml-auto cursor-pointer rounded-lg p-2 text-slate-500 transition-all duration-200 ease-out hover:scale-105 hover:bg-slate-100 hover:text-slate-800 motion-reduce:hover:scale-100 active:scale-95">
                        <span class="sr-only">Close menu</span>
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </label>
                </div>
                <nav class="flex flex-1 flex-col gap-0.5 p-3">
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Navigation</p>
                    <a href="{{ route('dashboard') }}"
                       class="group sidebar-link {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('monthly-summary.index') }}"
                       class="group sidebar-link {{ request()->routeIs('monthly-summary.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Monthly Summary
                    </a>
                    <a href="{{ route('users.index') }}"
                       class="group sidebar-link {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Users
                    </a>
                    <a href="{{ route('meals.index') }}"
                       class="group sidebar-link {{ request()->routeIs('meals.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Meals
                    </a>
                    <a href="{{ route('expenses.index') }}"
                       class="group sidebar-link {{ request()->routeIs('expenses.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Expenses
                    </a>
                    <a href="{{ route('advance-payments.index') }}"
                       class="group sidebar-link {{ request()->routeIs('advance-payments.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Advance Payments
                    </a>
                    <a href="{{ route('house-rents.index') }}"
                       class="group sidebar-link {{ request()->routeIs('house-rents.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        House Rent
                    </a>
                    <a href="{{ route('maid-bills.index') }}"
                       class="group sidebar-link {{ request()->routeIs('maid-bills.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50/90 hover:text-slate-900' }}">
                        <svg class="sidebar-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Maid Bills
                    </a>
                </nav>
            </aside>

            {{-- Main column --}}
            <div class="flex min-w-0 flex-1 flex-col">
                <header class="sticky top-0 z-20 flex h-14 shrink-0 items-center gap-4 border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-md sm:px-6 lg:px-8">
                    <label for="sidebar-drawer" class="inline-flex cursor-pointer items-center justify-center rounded-lg p-2 text-slate-600 transition-all duration-200 ease-out hover:scale-105 hover:bg-slate-100 hover:text-slate-900 motion-reduce:hover:scale-100 active:scale-95 lg:hidden">
                        <span class="sr-only">Open menu</span>
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </label>
                    <div class="flex min-w-0 flex-1 items-center">
                        <a href="{{ route('dashboard') }}" class="truncate text-lg font-semibold tracking-tight text-slate-900 transition-colors duration-200 ease-out hover:text-indigo-700">Mess App</a>
                    </div>
                </header>

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div id="app-flash-success"
                             data-dismiss-ms="4500"
                             class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900 shadow-sm transition-all duration-300 ease-out motion-reduce:transition-none motion-reduce:hover:scale-100 hover:scale-[1.01] hover:border-green-300/80 hover:shadow-md"
                             role="status"
                             aria-live="polite">
                            <svg class="mt-0.5 size-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="min-w-0 flex-1 leading-relaxed">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-200/90 bg-red-50 px-5 py-4 text-sm text-red-900 shadow-md shadow-red-900/5 ring-1 ring-red-900/[0.06] transition-[box-shadow,transform,border-color] duration-300 ease-out motion-reduce:transition-shadow motion-reduce:hover:translate-y-0 hover:-translate-y-px hover:border-red-300/70 hover:shadow-lg" role="alert">
                            <p class="font-semibold tracking-tight">Please fix the following:</p>
                            <ul class="mt-3 list-inside list-disc space-y-1.5 text-red-800">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>

        <footer role="contentinfo" aria-label="Copyright" class="relative shrink-0 border-t border-slate-200/90 bg-gradient-to-b from-white via-slate-50/90 to-slate-100/70 shadow-[inset_0_1px_0_0_rgba(255,255,255,0.9)]">
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-indigo-400/40 to-transparent" aria-hidden="true"></div>
            <div class="mx-auto flex max-w-5xl flex-col items-center gap-1.5 px-4 py-4 sm:gap-2 sm:py-5">
                <div class="inline-flex max-w-full flex-wrap items-center justify-center gap-x-3 gap-y-1 rounded-2xl border border-slate-200/90 bg-white/95 px-5 py-2.5 shadow-md shadow-slate-200/40 ring-1 ring-slate-900/[0.04] backdrop-blur-sm sm:gap-x-4 sm:px-6 sm:py-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-xs font-bold text-white shadow-inner shadow-indigo-900/20 ring-1 ring-white/25" aria-hidden="true">&copy;</span>
                    <span class="hidden h-6 w-px shrink-0 bg-gradient-to-b from-transparent via-slate-200 to-transparent sm:block" aria-hidden="true"></span>
                    <p class="text-center text-sm font-semibold tracking-tight text-slate-800 sm:text-base">
                        <span class="sr-only">Copyright </span>
                        <span class="tabular-nums text-slate-900">{{ date('Y') }}</span>
                        <span class="mx-1.5 text-slate-300" aria-hidden="true">&middot;</span>
                        <span class="bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Mess App</span>
                    </p>
                </div>
                <p class="text-center text-[0.7rem] font-medium uppercase tracking-widest text-slate-500 sm:text-xs">All rights reserved</p>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
