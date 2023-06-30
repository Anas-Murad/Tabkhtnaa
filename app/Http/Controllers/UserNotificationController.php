<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserNotificationRequest;
use App\Models\UserNotification;

class UserNotificationController extends Controller
{
    public function index()
    {
        return UserNotification::all();
    }

    public function store(UserNotificationRequest $request)
    {
        return UserNotification::create($request->validated());
    }

    public function show(UserNotification $userNotification)
    {
        return $userNotification;
    }

    public function update(UserNotificationRequest $request, UserNotification $userNotification)
    {
        $userNotification->update($request->validated());

        return $userNotification;
    }

    public function destroy(UserNotification $userNotification)
    {
        $userNotification->delete();

        return response()->json();
    }
}
