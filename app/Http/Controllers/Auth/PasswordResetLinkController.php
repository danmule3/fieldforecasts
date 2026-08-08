<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Deliberately generic response regardless of whether the email
        // exists, to avoid leaking which emails are registered.
        Password::sendResetLink($request->only('email'));

        return back()->with('status', __('If an account with that email exists, a reset link has been sent.'));
    }
}
