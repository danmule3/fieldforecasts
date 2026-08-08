<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Response;
use Illuminate\View\View;

class NewsletterController extends AdminController
{
    public function index(): View
    {
        return view('admin.newsletter.index', [
            'subscribers' => NewsletterSubscriber::orderByDesc('subscribed_at')->paginate(50),
            'activeCount' => NewsletterSubscriber::active()->count(),
        ]);
    }

    public function export(): Response
    {
        $rows = NewsletterSubscriber::active()->orderBy('email')->pluck('email');

        $csv = "email\n" . $rows->implode("\n");

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="newsletter-subscribers.csv"',
        ]);
    }
}
