<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a success response with a toast message
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function success_response($message, $data = [])
    {
        return redirect()->back()->with([
            'message' => $message,
            'type' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Return an error response with a toast message
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function error_response($message, $data = [])
    {
        return redirect()->back()->with([
            'message' => $message,
            'type' => 'error',
            'data' => $data
        ]);
    }

    /**
     * Return a warning response with a toast message
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function warning_response($message, $data = [])
    {
        return redirect()->back()->with([
            'message' => $message,
            'type' => 'warning',
            'data' => $data
        ]);
    }

    /**
     * Return an info response with a toast message
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function info_response($message, $data = [])
    {
        return redirect()->back()->with([
            'message' => $message,
            'type' => 'info',
            'data' => $data
        ]);
    }
}