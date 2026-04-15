@php
    $sidebarBase = 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition';
@endphp

<aside class="sticky top-4 h-[calc(100vh-2rem)] w-64 shrink-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
    <a href="{{ route('dashboard') }}" class="mb-4 block px-2 text-lg font-semibold tracking-tight text-slate-900">
        Mess App
    </a>

    <nav class="space-y-1">
        <a href="{{ route('dashboard') }}" class="{{ $sidebarBase }} {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            Dashboard
        </a>
        <a href="{{ route('monthly-summary.index') }}" class="{{ $sidebarBase }} {{ request()->routeIs('monthly-summary.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            Monthly Summary
        </a>
        <a href="{{ route('users.index') }}" class="{{ $sidebarBase }} {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            Users
        </a>
        <a href="{{ route('meals.index') }}" class="{{ $sidebarBase }} {{ request()->routeIs('meals.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            Meals
        </a>
        <a href="{{ route('expenses.index') }}" class="{{ $sidebarBase }} {{ request()->routeIs('expenses.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            Expenses
        </a>
        <a href="{{ route('advance-payments.index') }}" class="{{ $sidebarBase }} {{ request()->routeIs('advance-payments.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            Advance Payments
        </a>
        <a href="{{ route('house-rents.index') }}" class="{{ $sidebarBase }} {{ request()->routeIs('house-rents.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            House Rent
        </a>
        <a href="{{ route('maid-bills.index') }}" class="{{ $sidebarBase }} {{ request()->routeIs('maid-bills.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-gray-100 hover:text-slate-900' }}">
            Maid Bills
        </a>
    </nav>
</aside>
