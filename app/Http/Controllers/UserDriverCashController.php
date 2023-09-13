<?php

namespace App\Http\Controllers;

use App\Models\UserDriverCash;
use Illuminate\Http\Request;

class UserDriverCashController extends Controller
{
    public function index()
    {
        return UserDriverCash::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
            'total_cash' => ['required', 'numeric'],
        ]);

        return UserDriverCash::create($request->validated());
    }

    public function show(UserDriverCash $userDriverCash)
    {
        return $userDriverCash;
    }

    public function update(Request $request, UserDriverCash $userDriverCash)
    {
        $request->validate([
            'user_id' => ['required'],
            'total_cash' => ['required', 'numeric'],
        ]);

        $userDriverCash->update($request->validated());

        return $userDriverCash;
    }

    public function destroy(UserDriverCash $userDriverCash)
    {
        $userDriverCash->delete();

        return response()->json();
    }
}
