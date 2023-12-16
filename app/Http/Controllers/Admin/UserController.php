<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Models\Country;
use App\Models\Order;
use App\Models\User;
use App\Traits\FCMTrait;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Storage;
use App\DataTables\UsersDataTable;

class UserController extends Controller
{
    use HelperTrait , FCMTrait;

    public function usersByStatusType($status=null , $type=null  )
    {
        $pageTitle = "" ;
        switch ($type){
            case 'client' :
                $pageTitle = "عملاء التطبيق / الافراد" ;
                break;
            case 'delivery' :
                $pageTitle = "الموصلين / توصيل" ;
                break;

            case 'chef' :
                $pageTitle = "حسابات الطهاه " ;
                break;
        }
        switch ($status){

            case 'all' :
                $status = null;
                $pageTitle .= " " ;
                break;
            case 'pending' :
                $pageTitle .= " - حسابات بالانتظار" ;
                break;
            case 'active' :
                $pageTitle .= " - حسابات نشطه" ;
                break;

            case 'rejected' :
                $pageTitle .= " -حسابات مرفوضه" ;
                break;
            case 'blocked' :
                $pageTitle .= " -حسابات محظوره" ;
                break;
        }
        return (new UsersDataTable($type , $status))->render('admin.users.index'  , compact('pageTitle' , 'type' ,'status'));
    }
    public function index($status=null , $type=null )
    {
        $pageTitle = "جميع المستخدمين" ;

        switch ($type){
            case 'client' :
                $pageTitle = "عملاء التطبيق / الافراد" ;
                break;
            case 'delivery' :
                $pageTitle = "الموصلين / توصيل" ;
                break;

            case 'chef' :
                $pageTitle = "حسابات الطهاه " ;
                break;
        }
        switch ($status){

            case 'all' :
                $status = null;
                $pageTitle .= " " ;
                break;
            case 'pending' :
                $pageTitle .= " - حسابات بالانتظار" ;
                break;
            case 'active' :
                $pageTitle .= " - حسابات نشطه" ;
                break;

            case 'rejected' :
                $pageTitle .= " -حسابات مرفوضه" ;
                break;
            case 'blocked' :
                $pageTitle .= " -حسابات محظوره" ;
                break;
        }
        return (new UsersDataTable($type , $status))->render('admin.users.index'  , compact('pageTitle' , 'type' ,'status'));
    }

    public function update_status($id )
    {
        $user = User::findOrFail($id);

        $Notifications = [];
        if (request()->filled('account_status') && request()->input('account_status') !=$user->account_status ){

            $title='';
            $body='';
            switch (request()->input('account_status')){
                case 'pending':
                    $title='حسابك في الانتظار';
                    $body='تم تحويل حسابك الى قيد المراجعه';
                    break;

                case 'active':
                    $title='تم تفعيل حسابك بنجاح';
                    $body='تم تفعيل حسابك على طبختنا بنجاح , شكرا لك !';
                    break;


                case 'rejected':
                    $title='تم رفض عضويتك ';
                    $body='تم رفض حسابك على طبختنا , يرجى مراجعه التفاصيل ';
                    $user->rejection_count = $user->rejection_count + 1 ;
                    $user->save() ;

                    break;



                case 'blocked':
                    $title='تم حظؤ حسابك';
                    $body='تم حظر حسابك , ربما يكون بسبب انتهاك شروط الاستخدام , تواصل معنا في حال كان هذا خطا';
                    break;

            }
            $Notifications[]=[
                'user_id' =>$user->id,
                'title' =>$title,
                'body' =>$body,
                'screen' =>'account',
            ];
            $user->userStatuses()->create([
                'status'=>request()->input('account_status') ,
                'account_comment'=>request()->input('account_comment') ,
            ]) ;




        }


        if (request()->filled('can_delivery') && request()->input('can_delivery') !=$user->can_delivery ){
            $title='';
            $body='';
            switch (request()->input('can_delivery')){
                case 'request':
                    $title='تم تحويل طلب التوصيل الى المراجعه';
                    $body='لن تتمكن من التوصيل الى ان يتم مراجعه طلبك';
                    break;
                case 'yes':
                    $title='تم قبول طلبك لتوصيل';
                    $body='يمكنك البدء بتوصيل طلباتك بالفعل';
                    break;
                case 'rejected':
                    $title='تم رفض طلبك لتوصيل الطلبات';
                    $body='يرجى مراجعه ملفك الشخصي';
                    break;
            }
            $Notifications[]=[
                'user_id' =>$user->id,
                'title' =>$title,
                'body' =>$body,
                'screen' =>'account',
            ];
        }


        $data = request()->only(
            'account_status',
            'account_comment',
        ) ;
        $data['can_delivery'] = request()->boolean('can_delivery');
        $user->update($data) ;


        foreach ($Notifications as $Notification)
            $this->PushNotification($Notification['user_id'],$Notification);

        session()->flash('Success' , 'تم نحديث بيانات المستخدم بنجاح ');
        return redirect()->back() ;
    }

    public function edit($id = Null)
    {
        return $this->show($id);
        $user = User::findOrFail($id);

        $user->loadMissing([
            'galleryKitchen',
            'liveLocation',
            'userAddress',
            'documents',
        ]);

        if ($user->type=='type')
            $user->loadRates();


        return view('admin.users.show');
        return  $user ;

    }


    public function show($id = Null)
    {
        $user = User::findOrFail($id);
        $user->loadMissing([
            'galleryKitchen',
            'residenceCountry',
            'liveLocation',
            'userAddress' =>function($q){
                $q->with(
                    [
                        'city',
                        'country',
                    ]
                );
            },
            'documents',
        ]);
        if ($user->type=='chef')
            $user->loadRates();
        if ($user->type == 'client')
           $user_order = Order::where('user_id' , $user->id)->where('status', 'delivered')->count();
        elseif ($user->type == 'delivery')
            $user_order = Order::where('delivery_id' , $user->id)->where('status', 'delivered')->count();
        else
            $user_order = Order::where('chef_id' , $user->id)->where('status', 'delivered')->count();
        return view('admin.users.show' , compact('user' , 'user_order'));
    }
}
