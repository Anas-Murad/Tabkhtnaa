<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DriverCashDataTable;
use App\DataTables\DriverUserCashDataTable;
use App\DataTables\TransactionDataTable;
use App\DataTables\TransferRecordsDataTable;
use App\DataTables\TransferUserRecordsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TransferRecord;
use App\Models\User;
use App\Models\UserDriverCash;

class TransferController extends Controller
{
    public function driver_cash()
    {

        $pageTitle = "مستحقات " ;
        return (new DriverCashDataTable( ))
            ->render('admin.transfer.driver-cash'  , compact('pageTitle' ));

    }
  public function driver_cash_user($user_id)
    {
        $user= User::findOrFail($user_id);
        $pageTitle = " كاش السائق - " ;
        $pageTitle .= $user->name;

        $orders=null;
        $prices=null;
        $pending_prices=null;
        $done_prices=null;
        $to_user_pending_prices=null;

        if (!request()->ajax()){
            $orders = Order::whereDeliveryId($user->id)->count();
            $prices = UserDriverCash::whereUserId($user->id)->sum('total_cash');
            $pending_prices = UserDriverCash::whereUserId($user->id)->whereStatus('pending')->sum('total_cash');
            $done_prices = UserDriverCash::whereUserId($user->id)->whereStatus('completed')->sum('total_cash');
            $to_user_pending_prices = TransferRecord::whereToId($user->id)->whereTransferStatus('pending')->sum('amount');

        }



        return (new DriverUserCashDataTable( $user)) ->render('admin.transfer.driver-cash-user'  ,
            compact(
                'pageTitle' ,
                'user',
                'orders',
                'prices',
                'pending_prices',
                'done_prices',
                'to_user_pending_prices',
            ));



    }
  public function records($type= null)
    {

        $pageTitle = "مستحقات " ;
        switch ($type){
            case 'admin':   $pageTitle .='النظام' ;  break ;
            case 'delivery':   $pageTitle .='السائقين' ;  break ;
            case 'chef':   $pageTitle .='الطهاه' ;  break ;
        }

          return (new TransferRecordsDataTable( $type))
              ->render('admin.transfer.records'  , compact('pageTitle' , 'type'));

    }

    public function records_user($user_id= null)
    {


        $user= User::findOrFail($user_id);
        $pageTitle = "مستحقات العميل - " ;
        $pageTitle .= $user->name;



        $orders=null;
        $prices=null;
        $pending_prices=null;
        $done_prices=null;

        if (!request()->ajax()){

            if ($user->type =='chef')
                $orders = Order::whereChefId($user->id)->count();
                else
                $orders = Order::whereDeliveryId($user->id)->count();

            $prices = TransferRecord::whereToId($user->id)->sum('amount');
            $pending_prices = TransferRecord::whereToId($user->id)->whereTransferStatus('pending')->sum('amount');
            $done_prices = TransferRecord::whereToId($user->id)->whereTransferStatus('completed')->sum('amount');

        }

          return (new TransferUserRecordsDataTable( $user)) ->render('admin.transfer.records_user'  ,
              compact(
                  'pageTitle' ,
                  'user',
                  'orders',
                  'prices',
                  'pending_prices',
                  'done_prices',
              ));
    }

}
