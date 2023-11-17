<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ComplaintsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $admins = Admin::where('account_status' , 'active')->get();
        $user_id = null;
        if ($request->user_id)
            $user_id = $request->user_id;
        return (new ComplaintsDataTable($user_id))->render('admin.complaints.index'  , compact('admins' , 'user_id'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $data['admin_id'] = Auth::guard('admin')->id();
        $complaint = Complaint::findOrFail($data['id']);
        $complaint->update($data);
        session()->flash('Success' , 'تم تعديل  بنجاح ');
        return response()->json(['success' => true]);
    }
}
