<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Activity::class);

        $logs = Activity::with('causer')
            ->latest()
            ->paginate(30);

        return view('admin.activity-logs.index', compact('logs'));
    }
}
