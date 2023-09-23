<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;
use Illuminate\Http\Request;

class UserPointController extends Controller
{
    public function index()
    {
        return UserPoint::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
            'order_id' => ['required'],
            'points' => ['required'],
            'status' => ['required'],
        ]);

        return UserPoint::create($request->validated());
    }

    public function show(UserPoint $userPoint)
    {
        return $userPoint;
    }

    public function update(Request $request, UserPoint $userPoint)
    {
        $request->validate([
            'user_id' => ['required'],
            'order_id' => ['required'],
            'points' => ['required'],
            'status' => ['required'],
        ]);

        $userPoint->update($request->validated());

        return $userPoint;
    }

    public function destroy(UserPoint $userPoint)
    {
        $userPoint->delete();

        return response()->json();
    }
}
