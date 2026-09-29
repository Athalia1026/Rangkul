<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // 1. [Admin] Semua activity log dengan filter
    public function index(Request $request)
    {
        $this->validateFilters($request, [
            'user_id'      => 'nullable|string',
            'subject_type' => 'nullable|string',
            'subject_id'   => 'nullable|string',
            'search'       => 'nullable|string|max:255',
        ]);

        $logs = $this->filteredQuery($request)
            ->with('user:id,nama,email,account_type')
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->user_id))
            ->when($request->filled('subject_type'), fn ($q) => $q->where('subject_type', 'like', '%' . $request->subject_type))
            ->when($request->filled('subject_id'), fn ($q) => $q->where('subject_id', $request->subject_id))
            ->when($request->filled('search'), fn ($q) => $q->where('description', 'like', '%' . $request->search . '%'))
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data'   => $logs,
        ]);
    }

    // 2. [Admin] Detail satu activity log (termasuk previous_data & new_data)
    public function show(string $id)
    {
        $log = ActivityLog::with('user:id,nama,email,account_type')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $log,
        ]);
    }

    // 3. [Admin] Pilihan filter (modul & aksi yang pernah tercatat)
    public function filters()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'modules' => ActivityLog::distinct()->orderBy('module')->pluck('module'),
                'actions' => ActivityLog::distinct()->orderBy('action')->pluck('action'),
            ],
        ]);
    }

    // 4. [Semua role] Riwayat aktivitas milik user yang sedang login
    public function mine(Request $request)
    {
        $this->validateFilters($request);

        $logs = $this->filteredQuery($request)
            ->where('user_id', $request->user()->id)
            ->select(['id', 'action', 'module', 'subject_id', 'subject_type', 'description', 'ip_address', 'created_at'])
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data'   => $logs,
        ]);
    }

    private function validateFilters(Request $request, array $extraRules = []): void
    {
        $request->validate([
            'module'    => 'nullable|string|max:255',
            'action'    => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date|after_or_equal:date_from',
            'per_page'  => 'nullable|integer|min:1|max:100',
        ] + $extraRules);
    }

    private function filteredQuery(Request $request)
    {
        return ActivityLog::query()
            ->when($request->filled('module'), fn ($q) => $q->where('module', $request->module))
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->action))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest('created_at');
    }
}
