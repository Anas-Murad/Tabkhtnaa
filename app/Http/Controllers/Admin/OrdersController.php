<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrdersDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class OrdersController extends Controller
{
    public function index($status=null , $transactionStatus=null , $userID =null)
    {

        $pageTitle = "جميع الطلبات" ;
        $user =  null;

        if ($userID){
            $user = User::findOrFail($userID) ;
            switch ($user->type){
                case 'client':
                    $pageTitle = " طلبات المستخدم " .$user->name;
                break;

                case 'delivery':
                    $pageTitle = " طلبات الموصل " .$user->name;

                    break;

                case 'chef':
                    $pageTitle = " طلبات الطاهي " .$user->name;
                    break;

            }


        }




        switch ($status){

            case 'all' :
                $status = null;
                $pageTitle .= " " ;
                break;
            case 'pending' :
                $pageTitle .= " - طلبات بالانتظار" ;
                break;

            case 'confirmed' :
                $pageTitle .= " - طلبات مقبول" ;
                break;

            case 'prepare' :
                $pageTitle .= " - طلبات قيد التحضير" ;
                break;

            case 'prepared' :
                $pageTitle .= " - طلبات تم التحصير" ;
                break;
            case 'on_way' :
                $pageTitle .= " - طلبات في الطريق" ;
                break;

            case 'delivered' :
                $pageTitle .= " - طلبات تم التوصيل" ;
                break;

            case 'not_delivered' :
                $pageTitle .= " - طلبات لم يتم التوصيل" ;
                break;

            case 'rejected' :
                $pageTitle .= " - طلبات مرفوضة" ;
                break;

            case 'cancel' :
                $pageTitle .= " - طلبات ملغية" ;
                break;

            case 'not_ordered' :
                $pageTitle .= " - طلبات غير مطلوبه بعد" ;
                break;

        }

        switch ($transactionStatus){

            case 'all' :
                $transactionStatus = null;
                $pageTitle .= " " ;
                break;
            case 'pending' :
                $pageTitle .= " - دفع بالانتظار" ;
                break;
            case 'success' :
                $pageTitle .= " - دفع مكتمل" ;
                break;
            case 'cancel' :
                $pageTitle .= " - دفع ملغي" ;
                break;
        }


        return
            (new OrdersDataTable($status , $transactionStatus  ,$userID , $user))
           ->render('admin.orders.index'  , compact('pageTitle' , 'transactionStatus' ,'status' ,'user'));

    }
    public function show($id)
    {
        $order = Order::findOrFail($id);

        $order->load([
           'orderMeal'=>function($q){
               $q->with([
                   'meal',
                   'accessories'=>function($q){
                   $q->Trans();
                   },
                   'additions',
               ]);
           },


            'orderStatus'=>function($q){
                $q->with([
                    'actionBy',
                ]);
            },

            'address'=>function($q){
                $q->with([
                    'city',
                    'country',
                ]);
            },
           'user',
           'delivery',
           'chef',
           'Transaction',
           'TransactionHistory',
        ]);

//    return $order;

        return   view('admin.orders.show' ,  compact('order'));



    }

}
