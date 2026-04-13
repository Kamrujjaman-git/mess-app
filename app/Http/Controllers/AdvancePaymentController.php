<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvancePaymentController extends Controller
{
    public function index(): View
    {
        $payments = AdvancePayment::query()
            ->with('user')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('advance-payments.index', compact('payments'));
    }

    public function create(): View
    {
        return view('advance-payments.create', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function show(AdvancePayment $advancePayment): JsonResponse
    {
        $advancePayment->load('user');

        return response()->json($advancePayment);
    }

    public function edit(AdvancePayment $advancePayment): View
    {
        return view('advance-payments.edit', [
            'payment' => $advancePayment->load('user'),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);

        $payment = AdvancePayment::create($validated);
        $payment->load('user');

        if ($request->wantsJson()) {
            return response()->json($payment, 201);
        }

        return redirect()->route('advance-payments.index')->with('success', 'Advance payment recorded successfully.');
    }

    public function update(Request $request, AdvancePayment $advancePayment): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);

        $advancePayment->update($validated);
        $advancePayment->load('user');

        if ($request->wantsJson()) {
            return response()->json($advancePayment->fresh(['user']));
        }

        return redirect()->route('advance-payments.index')->with('success', 'Advance payment updated successfully.');
    }

    public function destroy(Request $request, AdvancePayment $advancePayment): JsonResponse|RedirectResponse
    {
        $advancePayment->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('advance-payments.index')->with('success', 'Advance payment deleted successfully.');
    }
}
