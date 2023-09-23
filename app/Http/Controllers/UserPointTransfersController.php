<?php

namespace App\Http\Controllers;

use App\Models\UserPointTransfers;
use Illuminate\Http\Request;

class UserPointTransfersController extends Controller
{
    public function index()
    {
        return UserPointTransfers::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
            'points' => ['required'],
            'offerd_type' => ['required'],
            'offerd_Id' => ['required'],
            'validity_date' => ['required', 'date'],
            'discount' => ['required'],
            'status' => ['required'],
        ]);

        return UserPointTransfers::create($request->validated());
    }

    public function show(UserPointTransfers $userPointTransfers)
    {
        return $userPointTransfers;
    }

    public function update(Request $request, UserPointTransfers $userPointTransfers)
    {
        $request->validate([
            'user_id' => ['required'],
            'points' => ['required'],
            'offerd_type' => ['required'],
            'offerd_Id' => ['required'],
            'validity_date' => ['required', 'date'],
            'discount' => ['required'],
            'status' => ['required'],
        ]);

        $userPointTransfers->update($request->validated());

        return $userPointTransfers;
    }

    public function destroy(UserPointTransfers $userPointTransfers)
    {
        $userPointTransfers->delete();

        return response()->json();
    }
}
