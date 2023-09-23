<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Models\Country;
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

        /*
                $countries= Country::with([
                    'configuration'=>function($q){
                        $q->whereIn('classification' , ['points' ,'distinction']) ;
                    }
                ])->whereHas('users')
        //            ->with('users')
                    ->select('id' , 'name')
                    ->get();


        // نقاط , ايرادات , اوردرات



                foreach ($countries as $country){


                    $config_key = $country->configuration->keyBy('config_key') ;

                     $users = User::whereResidenceCountryId($country->id)
                        ->withCount([
                            'chefOrders'=>function($q){},
                            'deliveryOrders'=>function($q){},
                            'sanctions'=>function($q){},
                            'userPoints'=>function($q){$q->where('status' ,'new');},
                        ])
                     ->withSum( 'chefTransfers' ,'amount')
                     ->withSum( 'deliveryTransfers' ,'amount')



        //            ->having('chef_orders_count' , '>=' , 1)
        //            ->having('chef_transfers_sum_amount' , '>=' , 1)

        //            ->having('delivery_orders_count' , '>=' , 1)
        //            ->having('delivery_transfers_sum_amount' , '>=' , 1)
        //            ->having('user_points_count' , '>=' , 1)
                    ->get();


                     $client_points_limit= $config_key['client_points_limit']->config_value;
                     $distinction_delivery_orders= $config_key['distinction_delivery_orders']->config_value;
                     $distinction_delivery_revenues= $config_key['distinction_delivery_revenues']->config_value;
                     $distinction_delivery_sanctions= $config_key['distinction_delivery_sanctions']->config_value;
                     $distinction_delivery_rate= $config_key['distinction_delivery_rate']->config_value;
                     $distinction_chef_orders= $config_key['distinction_chef_orders']->config_value;
                     $distinction_chef_revenues= $config_key['distinction_chef_revenues']->config_value;
                     $distinction_chef_sanctions= $config_key['distinction_chef_sanctions']->config_value;
                     $distinction_chef_rate= $config_key['distinction_chef_rate']->config_value;

                    foreach ($users as $user) {

                        $user->loadRates();
                        if ($user->type=='client'){
                            if($user->user_points_count >= $client_points_limit){
                                // add to point transfer



                            }
                        }


                        if ($user->type=='delivery'){
                            if(
                                $user->delivery_orders_count >= $distinction_delivery_orders &&
                                $user->delivery_transfers_sum_amount >= $distinction_delivery_revenues &&
                                $user->raties['rating_delivery'] >= $distinction_delivery_rate &&
                                $user->sanctions_count >= $distinction_delivery_sanctions
                            ){






                            }
                        }

                        if ($user->type=='chef'){
                            if(
                                $user->chef_orders_count >= $distinction_delivery_orders &&
                                $user->chef_transfers_sum_amount >= $distinction_delivery_revenues &&
                                $user->raties['rating_delivery'] >= $distinction_delivery_rate &&
                                $user->sanctions_count >= $distinction_delivery_sanctions
                            ){





                            }
                        }



                            return $user ;
                    }
        */
        /*
         *
: 0,
: 0,
: 0,
: null,
: null

         * */


//            ->chunk(200 , function ( $users){
//                foreach ($users as $user) {
//
//                    return $user ;
//                }
//            });

//        }



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


        $user->update(request()->only(
            'account_status',
            'can_delivery',
            'account_comment',
        )) ;


        foreach ($Notifications as $Notification)
            $this->PushNotification($Notification['user_id'],$Notification);

        session()->flash('Success' , 'تم نحديث بيانات المستخدم بنجاح ');
        return redirect()->back() ;
    }

    public function edit($id = Null)
    {
        $user = User::findOrFail($id);

        $user->loadMissing([
            'galleryKitchen',
            'liveLocation',
            'userAddress',
            'documents',
        ]);

        if ($user->type=='type')
            $user->loadRates();


        return  $user ;
        return view('admin.users.index');
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
        if ($user->type=='type')
            $user->loadRates();



        return view('admin.users.show' , compact('user'));

        return  $id ;
        return view('admin.users.index');
    }





}
