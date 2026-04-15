<header class="mb-6 flex items-center justify-between rounded-xl bg-white p-4 shadow-sm">
    <h1 class="text-lg font-semibold text-slate-900">Mess App</h1>

    <form method="get" action="{{ url()->current() }}" class="flex items-center gap-2">
        <label for="global-month-filter" class="sr-only">Month filter</label>
        <input id="global-month-filter" type="month" name="month" value="{{ request('month', date('Y-m')) }}"
               class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        <button type="submit" class="btn-cta">Apply</button>
    </form>
</header>
