<?php

namespace App\Http\Controllers\Admin ;

use App\Http\Controllers\Controller;
use App\Models\MailLog;
use Illuminate\Http\Request;

class MailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = MailLog::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('from_email', 'like', "%{$search}%")
                  ->orWhere('to_email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date from filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Date to filter
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get statistics
        $stats = [
            'total' => MailLog::count(),
            'success' => MailLog::where('status', 'success')->count(),
            'failed' => MailLog::where('status', 'failed')->count(),
        ];

        // Get paginated mail logs
        $mailLogs = $query->latest()->paginate(15)->withQueryString();

        return view('loggedTemp.MailLogs', compact('mailLogs', 'stats'));
    }
}