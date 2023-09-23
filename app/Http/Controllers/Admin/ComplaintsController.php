<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ComplaintsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintsController extends Controller
{
    public function index(ComplaintsDataTable $dataTable)
    {
        return $dataTable->render('admin.complaints.index');
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
