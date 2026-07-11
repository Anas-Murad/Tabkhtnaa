<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DoublePointsCampaignsDataTable;
use App\Http\Controllers\Controller;
use App\Models\DoublePointsCampaign;
use Illuminate\Http\Request;

class DoublePointsCampaignController extends Controller
{
    public function index(DoublePointsCampaignsDataTable $dataTable)
    {
        return $dataTable->render('admin.loyalty.campaigns.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'multiplier' => ['required', 'numeric', 'min:1.1', 'max:5'],
            'applies_to' => ['required', 'in:all,delivery,pick_up'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        DoublePointsCampaign::create($data);
        session()->flash('Success', 'تم إنشاء الحملة بنجاح');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'multiplier' => ['required', 'numeric', 'min:1.1', 'max:5'],
            'applies_to' => ['required', 'in:all,delivery,pick_up'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        DoublePointsCampaign::findOrFail($id)->update($data);
        session()->flash('Success', 'تم تحديث الحملة بنجاح');

        return redirect()->back();
    }

    public function destroy($id)
    {
        DoublePointsCampaign::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
