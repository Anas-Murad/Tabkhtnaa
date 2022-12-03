<?php

namespace App\Traits;
use DB;
use Image;
use Request;
use Storage;
use Str;
trait HelperTrait
{

    public function lang__()
    {
        return app()->getLocale();
    }


    public function getSerialNumber($first_char = 'T'): string
    {
        $last_s_number = UserMembership::select('membership_number')
            ->where('membership_number', 'like', "$first_char%")
            ->orderBy('id', 'DESC')
            ->first();

        if ($last_s_number) {
            $s_num = (int)filter_var($last_s_number->membership_number, FILTER_SANITIZE_NUMBER_INT);
            $s_num = abs($s_num) + 1;
            $s_num = $first_char . Str::padLeft($s_num, 5, '0');
        } else {
            $s_num = $first_char . "00001";
        }
        return $s_num;
    }


    public function return_Invalidate($validator)
    {
        return response()->json(array(
            'status' => false,
            "error_code" => 400,
            "error_msg" => $validator->errors()->first(),
            "data" => $validator->errors()
        ));
    }

    public function returnError($error_msg, $error_code = -1)
    {
        return response()->json(
            array(
                'status' => false, 'error_code' => $error_code, 'error_msg' => $error_msg
            )
        );
    }

    public function UN_AUTHENTICATED()
    {
        return response()->json(
            array(
                'status' => false,
                "error_code" => 100,
                "error_msg" => __('messages.unauthenticated'),
            )
        );
    }

    public function UN_AUTHORIZED()
    {
        return response()->json(
            array(
                'status' => false,
                "error_code" => 100,
                "error_msg" => __('messages.unauthenticated'),
            )
        );
    }


    public function returnDataWithOther($data, $other)
    {

        $custom_return = collect(['status' => true, 'error_code' => 0, 'error_msg' => __('messages.successfully'), 'other' => $other]);
        return response()->json($custom_return->merge($data));
    }

    public function returnPaginateData($data)
    {
        $custom_return = collect(['status' => true, 'error_code' => 0, 'error_msg' => __('messages.successfully'),]);
        return response()->json($custom_return->merge($data));
    }


    public function returnDataArray($data, $error_msg = null)
    {
        return response()->json(
            array(
                'status' => true, 'error_code' => 0, 'error_msg' => $error_msg ?? __('messages.successfully'), "data" => $data
            )
        );
    }


    public function returnDataArrayWithOther($data, $other)
    {
        return response()->json(
            array(
                'status' => true, 'error_code' => 0, 'error_msg' => __('messages.successfully'), "other" => $other, "data" => $data
            )
        );
    }


    public function returnCustomDataArray($data, $status = true, $error_code = 0, $error_msg = 'successfully')
    {
        return response()->json(
            array(
                'status' => $status, 'error_code' => $error_code, 'error_msg' => $error_msg, "data" => $data
            )
        );
    }

    public function returnErrorArray($error_msg, $data)
    {
        return response()->json(
            array(
                'status' => false, 'error_code' => -1, 'error_msg' => $error_msg, 'data' => $data
            )
        );
    }


    public function returnSuccess($error_msg)
    {
        return response()->json(
            array(
                'status' => true, 'error_code' => 0, 'error_msg' => $error_msg
            )
        );
    }


    function saveImage($photo, $folder)
    {
        $rand = Str::random(5);
        $file_extension = $photo->getClientOriginalExtension();
        $file_name = $rand . time() . '.' . $file_extension;
        $path = $folder;
        $photo->move($path, $file_name);
        return $path . '/' . $file_name;
    }


    function CalcAccount($course_id , $isUpdate=false ,CourseAccount $courseAccount = null )
    {



        $dataCourse = Course::select(
            'id',
            'price',
            'membership_price',
            'attendance_limit',
        )->find($course_id);

        $dataAttendance = DB::table('courses_users')
            ->selectRaw(" count(*) as reg_total ")
            ->selectRaw("(select count(*) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =1 ) as reg_memberships_count")
            ->selectRaw("(select count(*) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =0 ) as reg_visitors_count")
            ->selectRaw("(select count(*) as toss from courses_users as su where   su.courses_id = $course_id and   (su.price =0 or su.price is null )  ) as reg_free_count")
            ->selectRaw("(select sum(price) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =1 ) as reg_memberships_price")
            ->selectRaw("(select sum(price) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =0 ) as reg_visitors_price")
            ->selectRaw("(select count(*) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =1 and attendance > 3) as attendance_memberships_count")
            ->selectRaw("(select count(*) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =0 and attendance > 3) as attendance_visitors_count")
            ->selectRaw("(select count(*) as toss from courses_users as su where   su.courses_id = $course_id and  (su.price =0 or su.price is null )  and attendance > 3) as attendance_free_count")
            ->selectRaw("(select sum(price) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =1 and attendance > 3) as attendance_memberships_price")
            ->selectRaw("(select sum(price) as toss from courses_users as su where   su.courses_id = $course_id and su.is_member =0 and attendance > 3) as attendance_visitors_price")
            ->selectRaw("(select count(*) as toss from courses_users as su where   su.courses_id = $course_id and attendance > 3)  as attendance_total")
            ->selectRaw("(select sum(contract_ratio) as toss from course_trainers as su where   su.courses_id = $course_id  and contract_status='admin_confirmed')  as contract_ratio_total")
              ->where('courses_users.courses_id', $course_id)
            ->first();


        $Trainers = CourseTrainers::where('courses_id', $course_id)->select('id',
            'trainer_id',
            'courses_id',
            'contract_ratio',
        )->where('contract_status', 'admin_confirmed')->get();

       $total = $dataAttendance->attendance_visitors_price + $dataAttendance->attendance_memberships_price;
        $TrainersPrice =0;
        foreach ($Trainers as $Trainer) {
            $r = $Trainer->contract_ratio;
            $Trainer->price_ratio = $total * $r / 100;

            $TrainersPrice +=  $Trainer->price_ratio;
        }
//        return $dataAttendance;

        $Course= Course::find($course_id);


        $data =[
            "courses_id" =>$course_id,
            "price"=>$Course->price ??0,
            "membership_price"=>$Course->membership_price ?? 0 ,
            "reg_memberships_count"=>$dataAttendance->reg_memberships_count ?? 0 ,
            "reg_visitors_count" =>$dataAttendance->reg_visitors_count ?? 0 ,
            "reg_free_count" =>$dataAttendance->reg_free_count ?? 0 ,
            "attendance_limit" =>$Course->attendance_limit ?? 0 ,
            "attendance_memberships_count" =>$dataAttendance->attendance_memberships_count ?? 0,

            "attendance_visitors_count" =>$dataAttendance->attendance_visitors_count ?? 0,
            "attendance_free_count" =>$dataAttendance->attendance_free_count ?? 0,
            "reg_memberships_price" =>$dataAttendance->reg_memberships_price ?? 0,
            "reg_visitors_price" =>$dataAttendance->reg_visitors_price ?? 0,
            "attendance_memberships_price" =>$dataAttendance->attendance_memberships_price ?? 0,
            "attendance_visitors_price" =>$dataAttendance->attendance_visitors_price ?? 0,



            "reg_total" =>$dataAttendance->reg_total ?? 0,
            "attendance_total" =>$dataAttendance->attendance_total ?? 0,
            "contract_ratio_total" =>$dataAttendance->contract_ratio_total ?? 0,
            "trainers_price_total" =>$TrainersPrice ?? 0,
            "status"=>'opened',
            //    "trainer_receipt_id",
            "notes" =>'توليد تلقائي',
        ] ;


        if ($isUpdate && $courseAccount){
            $courseAccount->update($data) ;
            $courseAccount->trainers()->delete();
        }else{
            $courseAccount =   CourseAccount::create( $data);
        }
        foreach ($Trainers as $Trainer) {
            $courseAccount->trainers()->create([
                'trainer_id'=>$Trainer->id ,
                'ratio'=>$Trainer->contract_ratio ,
                'price'=>$Trainer->price_ratio ,
                "status"=>'pending',
            ]);
        }




    }
}
