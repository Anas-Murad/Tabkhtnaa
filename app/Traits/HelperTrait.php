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

    public function return_Invalidate($exception)
    {
        return response()->json(array(
            'status' => false,
            "error_code" => $exception->status,
            "error_msg" => $exception->getMessage(),
            "data" => $exception->errors()
        )  , $exception->status);
    }

    public function returnError($error_msg, $error_code = -1  , $status=200)
    {
        return response()->json(
            array(
                'status' => false, 'error_code' => $error_code, 'error_msg' => $error_msg
            ) ,$status
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

    public function returnPaginateData($data)
    {
        $custom_return = collect(['status' => true, 'error_code' => 0, 'error_msg' => __('messages.successfully'),]);
        return response()->json($custom_return->merge($data));
    }

    public function returnPaginateDataWithOther($data , $other , $otherName = 'other')
    {
        $custom_return = collect([
            'status' => true,
            'error_code' => 0,
            'error_msg' => __('messages.successfully'),
            $otherName => $other,
            ]);
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

    public function returnDataArrayWithOther($data, $other , $otherName = 'other')
    {
        return response()->json(
            array(
                'status' => true, 'error_code' => 0, 'error_msg' => __('messages.successfully'), $otherName=> $other, "data" => $data
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

    function saveImageUrl($photo, $folder)
    {
        $rand = Str::random(5);
        $contents = file_get_contents($photo);
        $file_name = $rand . time() . '.png';
        $path = $folder.$file_name;
        file_put_contents($path, $contents);
        return $path ;
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
}
