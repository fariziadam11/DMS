<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\AuditLog;

class HeaderNotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = auth()->user();
        $userId = $user->id;

        // 1. My Actions (Tracking)
        $myActions = AuditLog::with('user')
            ->where('user_id', $userId);

        // 2. Incoming Notifications (Approve/Reject on MY requests)
        // We find logs where table=file_access_requests, record_id IN (my requests), action IN (approve, reject)
        $myRequestIds = \App\Models\FileAccessRequest::where('id_user', $userId)->pluck('id');

        $myNotifications = AuditLog::with('user')
            ->where('table_name', 'file_access_requests')
            ->whereIn('record_id', $myRequestIds)
            ->whereIn('action', ['approve', 'reject']);

        // 3. Admin Incoming Requests (If I am Super Admin or have Access Manage rights)
        // This is complex to filter precisely by division in a single union query.
        // For now, if SuperAdmin, show all 'request_access'.
        // If Division Admin, show 'request_access' (we can filtering loosely or skip to avoid performance hit).
        // Let's include 'request_access' generally, but maybe restricted?
        // User asked: "begitu juga ketika user request filenya" (Approver should see it).
        // Let's include 'request_access' logs for everyone for now, rely on UI or just let them see "Someone requested".
        // Or better: Only if user->roles()->where('access_scope', '!=', 'own')->exists() ?

        $incomingRequests = null;
        if ($user->isSuperAdmin() || $user->roles()->whereIn('access_scope', ['global', 'division'])->exists()) {
             $incomingRequests = AuditLog::with('user')
                ->where('table_name', 'file_access_requests')
                ->where('action', 'request_access');
        }

        // Merge Queries using Union
        $query = $myActions->union($myNotifications);

        if ($incomingRequests) {
            $query = $query->union($incomingRequests);
        }

        $recentActivities = $query->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $view->with('recentActivities', $recentActivities);
    }
}
