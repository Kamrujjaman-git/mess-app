<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesControllerErrors;
use App\Models\Meal;
use App\Models\User;
use App\Support\Month;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class MealController extends Controller
{
    use HandlesControllerErrors;

    public function create(): View
    {
        $users = User::query()->orderBy('name')->get();
        if ($users->isEmpty()) {
            $this->logMissingData('meals.create_missing_users');
        }

        return view('meals.create', ['users' => $users]);
    }

    public function edit(Meal $meal): View
    {
        return view('meals.edit', [
            'meal' => $meal->load('user'),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        try {
            [$month, $year, $monthNum] = Month::normalize($request->input('month'));

            $meals = Meal::query()
                ->with('user')
                ->forMonth($year, $monthNum)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get();

            if ($request->wantsJson()) {
                return response()->json($meals);
            }

            return view('meals.index', [
                'meals' => $meals,
                'month' => $month,
                'monthLabel' => date('F Y', strtotime($month.'-01')),
            ]);
        } catch (Throwable $e) {
            $this->logControllerError($e, 'meals.index_failed', [
                'month' => $request->input('month'),
            ]);
            return $this->errorResponse($request, 'meals.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $request->validate([
                'user_id' => [
                    'required',
                    'exists:users,id',
                    Rule::unique('meals', 'user_id')->where(
                        fn ($query) => $query->whereDate('date', $request->input('date'))
                    ),
                ],
                'date' => ['required', 'date_format:Y-m-d'],
                'lunch' => ['sometimes', 'boolean'],
                'dinner' => ['sometimes', 'boolean'],
            ]);

            $meal = Meal::create([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'lunch' => $request->boolean('lunch'),
                'dinner' => $request->boolean('dinner'),
            ]);
            $meal->load('user');

            if ($request->wantsJson()) {
                return response()->json($meal, 201);
            }

            return redirect()->route('dashboard')->with('success', 'Meal entry saved successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'meals.insert_failed', [
                'user_id' => $request->input('user_id'),
                'date' => $request->input('date'),
            ]);
            return $this->errorResponse($request, 'meals.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal): JsonResponse
    {
        $meal->load('user');

        return response()->json($meal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => [
                    'required',
                    'integer',
                    'exists:users,id',
                    Rule::unique('meals', 'user_id')->where(
                        fn ($query) => $query->whereDate('date', $request->input('date'))
                    )->ignore($meal->id),
                ],
                'date' => ['required', 'date_format:Y-m-d'],
                'lunch' => ['sometimes', 'boolean'],
                'dinner' => ['sometimes', 'boolean'],
            ]);

            $meal->update([
                'user_id' => $validated['user_id'],
                'date' => $validated['date'],
                'lunch' => $request->boolean('lunch'),
                'dinner' => $request->boolean('dinner'),
            ]);
            $meal->load('user');

            if ($request->wantsJson()) {
                return response()->json($meal->fresh(['user']));
            }

            return redirect()->route('meals.index')->with('success', 'Meal updated successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'meals.update_failed', [
                'id' => $meal->id,
            ]);
            return $this->errorResponse($request, 'meals.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Meal $meal): JsonResponse|RedirectResponse
    {
        try {
            $meal->delete();

            if ($request->wantsJson()) {
                return response()->json(null, 204);
            }

            return redirect()->route('meals.index')->with('success', 'Meal deleted successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'meals.delete_failed', [
                'id' => $meal->id,
            ]);
            return $this->errorResponse($request, 'meals.index');
        }
    }
}
