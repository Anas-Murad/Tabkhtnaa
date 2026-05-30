<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\complaint\CreateComplaintRequest;
use App\Models\BusinessSetting;
use App\Models\Complaint;
use App\Models\Order;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    use HelperTrait;

    public function getComplaint()
    {
        $complaints = Complaint::where('user_id' , Auth::id())->get();
        return $this->returnDataArray($complaints);
    }

    public function createComplaint(CreateComplaintRequest $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return $this->returnError('Order not found', 404, 404);
        }

        $timeSetting = BusinessSetting::where('key', 'time_creat_complaint')->first();
        $allowedMinutes = (int) ($timeSetting->value ?? 10080);

        $deadline = Carbon::parse($order->created_at)->addMinutes($allowedMinutes);
        if (Carbon::now()->gt($deadline)) {
            return $this->returnError('Time end send Conplan the order');
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->saveImage($request->photo, 'uploads/Complaints');
        }

        $complaints = Complaint::create($data);
        return $this->returnDataArray($complaints);
    }
}
