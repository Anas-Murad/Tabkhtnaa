<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return Notification::all();
    }

    public function store(NotificationRequest $request)
    {
        return Notification::create($request->validated());
    }

    public function show(Notification $notification)
    {
        return $notification;
    }

    public function update(NotificationRequest $request, Notification $notification)
    {
        $notification->update($request->validated());

        return $notification;
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json();
    }
}
