<?php

namespace App\Http\Controllers;

use App\Models\UserDistinction;
use Illuminate\Http\Request;

class UserDistinctionController extends Controller
{
    public function index()
    {
        return UserDistinction::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
            'from_date' => ['required'],
            'to_date' => ['required'],
            'status' => ['required'],
        ]);

        return UserDistinction::create($request->validated());
    }

    public function show(UserDistinction $userDistinction)
    {
        return $userDistinction;
    }

    public function update(Request $request, UserDistinction $userDistinction)
    {
        $request->validate([
            'user_id' => ['required'],
            'from_date' => ['required'],
            'to_date' => ['required'],
            'status' => ['required'],
        ]);

        $userDistinction->update($request->validated());

        return $userDistinction;
    }

    public function destroy(UserDistinction $userDistinction)
    {
        $userDistinction->delete();

        return response()->json();
    }
}
