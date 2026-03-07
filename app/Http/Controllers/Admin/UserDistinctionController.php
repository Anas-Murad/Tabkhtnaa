<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrdersDataTable;
use App\DataTables\UserDistinctionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\TransferRecord;
use App\Models\UserDistinction;
use App\Traits\HelperTrait;

class UserDistinctionController extends Controller
{

use HelperTrait;
    public function approved($id)
    {
        $UserDistinction=   UserDistinction::
        findOrFail($id);

        $UserDistinction->update([
            'status' => 'active',
            'from_date' =>  request()->from_date,
            'to_date' =>  request()->to_date,
        ]);


        session()->flash('Success' , 'تم التفعيل بنجاح');
        return  redirect()->back() ;
        return  $this->returnSuccess('تم التفعيل بنجاح') ;
    }

    public function reject($id)
    {
        $UserDistinction=   UserDistinction::
        with('user')->
        findOrFail($id);

        $UserDistinction->update([
            'status' => 'rejected'
        ]);
        session()->flash('Success' , 'تم الرفض بنجاح');
        return  $this->returnSuccess('تم الرفض بنجاح') ;
    }


    public function show($id)
    {

      $UserDistinction=   UserDistinction::
        with('user.userAddress')->
        findOrFail($id);


        $user = $UserDistinction->user;
        $type = $user->type;
        $prev_distinction_at= $user->prev_distinction_at ;
        $countryId = $user ->userAddress[0]->country_id;



        $country = Country::with([
            'configuration' => function ($q) {
                $q->whereIn('classification', ['points', 'distinction']);
            }
        ])->whereHas('users')
            ->select('id', 'name')
            ->find($countryId);

        $config_key = $country->configuration->keyBy('config_key');
        $client_points_limit = $config_key['client_points_limit']->config_value;
        $distinction_delivery_orders = $config_key['distinction_delivery_orders']->config_value;
        $distinction_delivery_revenues = $config_key['distinction_delivery_revenues']->config_value;
        $distinction_delivery_sanctions = $config_key['distinction_delivery_sanctions']->config_value;
        $distinction_chef_orders = $config_key['distinction_chef_orders']->config_value;
        $distinction_chef_revenues = $config_key['distinction_chef_revenues']->config_value;
        $distinction_chef_sanctions = $config_key['distinction_chef_sanctions']->config_value;


        $user->loadCount([
            $type.'Orders as PeriodTotalOrders' => function ($q) use ($prev_distinction_at) {
                $q->when($prev_distinction_at, function ($q) use ($prev_distinction_at) {
                    $q->where('created_at', '>', $prev_distinction_at);
                });
            },
            'sanctions as PeriodTotalSanctions' => function ($q) use ($prev_distinction_at) {
                $q->when($prev_distinction_at, function ($q) use ($prev_distinction_at) {
                    $q->where('created_at', '>', $prev_distinction_at);
                });
            },

            $type.'Orders as TotalOrders',
            'sanctions as TotalSanctions' ,

        ]);
        $revenues = TransferRecord::whereToId($user->id)->whereToType($type)
            ->when($prev_distinction_at, function ($q) use ($prev_distinction_at) {
                $q->where('created_at', '>', $prev_distinction_at);
            })->sum('amount');


        $revenuesTotal = TransferRecord::whereToId($user->id)->whereToType($type) ->sum('amount');





        $OldUserDistinctionList =  UserDistinction::whereUserId($UserDistinction->user_id)
            ->whereNot('id' , $id)->latest()->limit(10)->get();




        $l= [
            'UserDistinction'=>$UserDistinction,
            'type'=>$type,
            'prev_distinction_at'=>$prev_distinction_at,
            'country'=>$country,
            'config_key'=>$config_key,
            'client_points_limit'=>$client_points_limit,
            'distinction_delivery_orders'=>$distinction_delivery_orders,
            'distinction_delivery_revenues'=>$distinction_delivery_revenues,
            'distinction_delivery_sanctions'=>$distinction_delivery_sanctions,
            'distinction_chef_orders'=>$distinction_chef_orders,
            'distinction_chef_revenues'=>$distinction_chef_revenues,
            'distinction_chef_sanctions'=>$distinction_chef_sanctions,
            'revenues'=>$revenues,
            'OldUserDistinctionList'=>$OldUserDistinctionList,
        ];
        return view('admin.distinction.show'  , compact(
            'UserDistinction',
            'user',
            'type',
            'prev_distinction_at',
            'country',
            'config_key',
            'client_points_limit',
            'distinction_delivery_orders',
            'distinction_delivery_revenues',
            'distinction_delivery_sanctions',
            'distinction_chef_orders',
            'distinction_chef_revenues',
            'distinction_chef_sanctions',
            'revenuesTotal',
            'revenues',
            'OldUserDistinctionList',
        ));
    }


    public function index($status = null  , $user_id = 2)
    {


        $pageTitle = "سجلات التمييز" ;
        switch ($status){
            case 'new' :
                $pageTitle .= " - جديد" ;
                break;

            case 'active' :
                $pageTitle .= " - نشط" ;
                break;

            case 'ended' :
                $pageTitle .= " - منتهي" ;
                break;

            case 'rejected' :
                $pageTitle .= " - مرفوض" ;
                break;


        }

        $userID = null;
        if ($user_id)
            $userID = $user_id;

        return
            (new UserDistinctionDataTable($status , $userID ))
                ->render('admin.distinction.index'  , compact('pageTitle' ));
//                ->render('admin.orders.index'  , compact('pageTitle' , 'transactionStatus' ,'status' ,'user'));




    }


}
