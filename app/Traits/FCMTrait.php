<?php


namespace App\Traits;

use App\Models\Device;
use App\Models\Notification;

trait FCMTrait
{


    protected $serverKey = 'xxxxx';

    public function PushNotification($user_id, array $data)
    {

     $Notification=      Notification::create([
         'body' => $data['body'] ??  null ,
         'title' => $data['title'] ??  null ,
         'order_id' => $data['order_id'] ??  null ,
         'data' => $data ??  null ,
     ]) ;
        $data['notification_id']=$Notification->id ;
        if (is_array($user_id)){
            foreach ($user_id as $user_idItem)
            $Notification->users()->create([
                'user_id'=>$user_idItem,
            ]);
        }else{
            $Notification->users()->create([
                'user_id'=>$user_id,
            ]);
        }
        return ;
        $this->FCMNotification($user_id, $data);
    }
    public function FCMNotification($user_id, array $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        ### sound = 1
        if ($user_id)
            if (is_array($user_id)) {
                $FcmToken = Device::whereIn('user_id', $user_id)
                    ->where('device_type', '!=', 4)
                    ->whereHas('user', function ($q) {
                        $q->where('notification_type', 'sound');
                    })->whereNotNull('token')->pluck('token')->all();
            } else {
                $FcmToken = Device::where('user_id', $user_id)
                    ->where('device_type', '!=', 4)
                    ->whereHas('user', function ($q) {
                        $q->where('notification_type', 'sound');
                    })->whereNotNull('token')->pluck('token')->all();
            }
        else
            $FcmToken = Device::whereHas('user', function ($q) {
                $q->where('notification_type', 'sound');
            })
                ->where('device_type', '!=', 4)
                ->whereNotNull('token')->pluck('token')->all();
        if (!empty($FcmToken)) {
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => $data['title'],
                    "body" => $data['body'],
                    "priority" => "high",
                ],
                "data" => $data['data'],
            ];


            $encodedData = json_encode($data);
            $headers = [
                'Authorization:key=' . $this->serverKey,
                'Content-Type: application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

            $result = curl_exec($ch);

            if ($result === FALSE) {
                // die('Curl failed: ' . curl_error($ch));
            }
        }

    }

}
