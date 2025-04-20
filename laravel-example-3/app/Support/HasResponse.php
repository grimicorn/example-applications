<?php

namespace App\Support;

use Illuminate\Http\Request;

trait HasResponse
{
    /**
     * Handles a success response.
     *
     * @param  string  $status
     * @param  Request $request
     * @param  string|null  $route
     * @param  array   $data
     *
     * @return Illuminate\Http\RedirectResponse|array
     */
    protected function successResponse($status, ?Request $request = null, $route = null, $data = [])
    {
        $request = is_null($request) ? request() : $request;
        $redirect = is_null($route) ? back() : redirect($route);
        $data = array_merge([
            'status' => $status,
            'success' => true,
        ], $data);

        // Handle JSON request.
        if ($request->expectsJson()) {
            // Store the status message for AJAX forms that redirect.
            if ($request->has('enable_redirect')) {
                session()->flash('success', true);
                session()->flash('status', $status);
            }

            return array_merge($data, [
                'redirect' => $redirect->getTargetUrl(),
            ]);
        }

        return $redirect->with($data);
    }

    protected function abortResponse($message, $jsonStatus = 422)
    {
        if (request()->expectsJson()) {
            return response()->json(['general_error' => $message], $jsonStatus);
        }

        return back()->with('general-error', $message);
    }
}
