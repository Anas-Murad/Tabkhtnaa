<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrdersDataTable;
use App\DataTables\UserDistinctionDataTable;
use App\Http\Controllers\Controller;

class UserDistinctionController extends Controller
{


    public function index($status = null)
    {

/*
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

*/

        $status = null;
        $pageTitle = " " ;
        return
            (new UserDistinctionDataTable($status ))
                ->render('admin.distinction.index'  , compact('pageTitle' ));
//                ->render('admin.orders.index'  , compact('pageTitle' , 'transactionStatus' ,'status' ,'user'));




    }


}
