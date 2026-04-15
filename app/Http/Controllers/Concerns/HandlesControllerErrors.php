<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

trait HandlesControllerErrors
{
    private string $genericUiErrorMessage = 'Something went wrong, please try again';

    protected function logControllerError(Throwable $exception, string $action, array $context = []): void
    {
        Log::error($action, array_merge($context, [
            'error' => $exception->getMessage(),
            'exception' => get_class($exception),
        ]));
    }

    protected function logMissingData(string $action, array $context = []): void
    {
        Log::warning($action, $context);
    }

    protected function errorResponse(Request $request, string $redirectRoute, array $routeParams = []): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $this->genericUiErrorMessage,
            ], 500);
        }

        return redirect()->route($redirectRoute, $routeParams)->with('error', $this->genericUiErrorMessage);
    }
}

