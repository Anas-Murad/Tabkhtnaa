<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AuditTrailsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->limit(500)->get();
        $admins = Admin::select('id', 'name', 'email')->orderBy('name')->get();
        $events = AuditTrail::query()->distinct()->orderBy('event')->pluck('event');
        $auditableTypes = AuditTrail::query()
            ->whereNotNull('auditable_type')
            ->distinct()
            ->orderBy('auditable_type')
            ->pluck('auditable_type')
            ->map(fn ($type) => class_basename($type));

        return (new AuditTrailsDataTable())->render('admin.audit_trails.index', compact(
            'users',
            'admins',
            'events',
            'auditableTypes'
        ));
    }

    public function show(int $id)
    {
        $audit = AuditTrail::with(['user:id,name,email,mobile', 'admin:id,name,email'])->findOrFail($id);

        return response()->json($audit);
    }
}
