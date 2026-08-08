<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function __construct(private readonly NewsletterService $newsletter)
    {
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);

        $this->newsletter->subscribe($data['email']);

        return back()->with('status', 'newsletter-subscribed');
    }

    public function unsubscribe(string $token): View
    {
        $success = $this->newsletter->unsubscribe($token);

        return view('newsletter.unsubscribed', ['success' => $success]);
    }
}
