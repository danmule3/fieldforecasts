<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends AdminController
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')
            ->when($request->event, fn ($q, $v) => $q->where('event', 'like', "%{$v}%"))
            ->orderByDesc('created_at')
            ->paginate(40)
            ->withQueryString();

        return view('admin.logs.index', ['logs' => $logs]);
    }
}
