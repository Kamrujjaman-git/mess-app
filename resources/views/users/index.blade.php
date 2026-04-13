@extends('layouts.app')

@section('title', 'Users — Mess App')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Users</h1>
            <p class="mt-1 text-sm text-slate-500">Members in the mess.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-cta">
            Add user
        </a>
    </div>

    @if ($users->isEmpty())
        <div class="empty-panel">
            No users yet. <a href="{{ route('users.create') }}" class="link-inline">Create one</a>.
        </div>
    @else
        <div class="table-surface">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-left text-sm text-slate-700">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/95">
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Name</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Role</th>
                            <th scope="col" class="whitespace-nowrap px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($users as $user)
                            <tr class="table-row-interactive">
                                <td class="px-6 py-5 font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="px-6 py-5 text-slate-600">{{ $user->email }}</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $user->role }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-3">
                                        <a href="{{ route('users.edit', $user) }}" class="action-link">Edit</a>
                                        <form action="{{ route('users.destroy', $user) }}" method="post" class="inline"
                                              onsubmit="return confirm('Delete this user? This cannot be undone.');">
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
