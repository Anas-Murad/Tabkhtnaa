<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LoyaltyTiersDataTable;
use App\Http\Controllers\Controller;
use App\Models\LoyaltyTier;
use Illuminate\Http\Request;

class LoyaltyTierController extends Controller
{
    public function index(LoyaltyTiersDataTable $dataTable)
    {
        return $dataTable->render('admin.loyalty.tiers.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'level' => ['required', 'integer', 'min:1'],
            'min_lifetime_spending' => ['required', 'numeric', 'min:0'],
            'points_multiplier' => ['required', 'numeric', 'min:1', 'max:10'],
            'min_redemption_points' => ['nullable', 'integer', 'min:0'],
            'birthday_bonus_multiplier' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        LoyaltyTier::create($data);
        session()->flash('Success', 'تم إضافة المستوى بنجاح');

        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:loyalty_tiers,id'],
            'name' => ['required', 'string', 'max:50'],
            'level' => ['required', 'integer', 'min:1'],
            'min_lifetime_spending' => ['required', 'numeric', 'min:0'],
            'points_multiplier' => ['required', 'numeric', 'min:1', 'max:10'],
            'min_redemption_points' => ['nullable', 'integer', 'min:0'],
            'birthday_bonus_multiplier' => ['nullable', 'numeric', 'min:1', 'max:10'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        LoyaltyTier::findOrFail($data['id'])->update($data);
        session()->flash('Success', 'تم تعديل المستوى بنجاح');

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        LoyaltyTier::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
