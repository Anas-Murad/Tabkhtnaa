<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\complaint\CreateComplaintRequest;
use App\Http\Requests\api\v1\complaint\UpdateComplaintRequest;
use App\Models\BusinessSetting;
use App\Models\Complaint;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $time_creat_complaint = BusinessSetting::where('key' , 'time_creat_complaint')->first();
        if ($request->order_id >= Carbon::now()->addMinutes($time_creat_complaint->value))
        {
            $data = $request->all();
            $data['user_id'] = Auth::id();
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->saveImage($request->photo, 'uploads/Complaints');
            }
            $complaints = Complaint::create($data);
            return $this->returnDataArray($complaints);
        }else
            return $this->returnError('Time end send Conplan the order');
    }
}
