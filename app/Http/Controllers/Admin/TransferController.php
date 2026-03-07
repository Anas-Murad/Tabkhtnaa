<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DriverCashDataTable;
use App\DataTables\DriverUserCashDataTable;
use App\DataTables\TransactionDataTable;
use App\DataTables\TransferDataTable;
use App\DataTables\TransferRecordCompletedDataTable;
use App\DataTables\TransferRecordsDataTable;
use App\DataTables\TransferUserRecordsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transfer;
use App\Models\TransferRecord;
use App\Models\User;
use App\Models\UserDriverCash;
use App\Traits\HelperTrait;
use Illuminate\Http\Client\Request;

class TransferController extends Controller
{

    use HelperTrait ;
    public function driver_cash()
    {

        $pageTitle = "اجمالي الكاش مع السائقين " ;
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
  public function records($type= null , $status= null)
    {

        $pageTitle = "مستحقات " ;
        switch ($type){
            case 'admin':   $pageTitle .='النظام' ;  break ;
            case 'delivery':   $pageTitle .='السائقين' ;  break ;
            case 'chef':   $pageTitle .='الطهاه' ;  break ;
        }
        if ($status == 'checked'){
            $pageTitle .=' مؤكده بانتظار التحويل ' ;
        }

        if ($status == 'completed'){
            $pageTitle .=' تم التحويل التحويل ' ;
        }

          return (new TransferRecordsDataTable( $type , $status))
              ->render('admin.transfer.records'  , compact('pageTitle' , 'type'));

    }

  public function transfer($type= null )
    {
        $pageTitle = "حركات السداد " ;
        switch ($type){
            case 'admin':   $pageTitle .='النظام' ;  break ;
            case 'delivery':   $pageTitle .='السائقين' ;  break ;
            case 'chef':   $pageTitle .='الطهاه' ;  break ;
        }
          return (new TransferDataTable( $type ))
              ->render('admin.transfer.index'  , compact('pageTitle' , 'type'));

    }


  public function transfer_records($transfer_id= null )
    {
        $transfer = Transfer:: with('to')-> findOrFail($transfer_id);

        $pageTitle = "تفاصيل حركه السداد رقم #" .$transfer->id ;
        $pageTitle .= ' العميل '.$transfer->to->name ;

          return (new TransferRecordCompletedDataTable( $transfer_id ))
              ->render('admin.transfer.record_completed'  , compact('pageTitle' , 'transfer'));


    }








    public function records_checked($id= null)
    {
        $tr = TransferRecord::findOrFail($id);
        $data = request()->only('admin_notes');
        $data['admin_checked'] = true ;
        $tr->update($data );
        return $this->returnSuccess('تم تأكيد العميله بنجاح');
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
    public function transfer_screen($user_id= null)
    {

        $user= User::findOrFail($user_id);

       $TransferRecord = TransferRecord::whereToId($user->id)
        ->whereAdminChecked(true)
        ->whereTransferStatus('pending')->get();


       return view('admin.transfer.transfer_screen' , compact(
           'user',
           'TransferRecord',
       )) ;
    }


    public function do_transfer($user_id= null)
    {

        $user= User::findOrFail($user_id);


        $trans_ids=collect (request()->submitData)->pluck('trans_id') ;

        $TransferRecord = TransferRecord::whereToId($user->id)
            ->whereIn('id' , $trans_ids)
            ->whereAdminChecked(true)
            ->whereTransferStatus('pending')
            ->get();

        abort_if($TransferRecord->count() <=0 ,403);
        $Transfer = Transfer::create([
            'from_type' =>'admin',
//            'from_id ' =>auth()->id(),
            'to_type' =>$user->type,
            'to_id' =>$user->id,
            'amount' =>request()->total,
        ]);
        TransferRecord::whereToId($user->id)
            ->whereIn('id' , $trans_ids)
            ->whereAdminChecked(true)
            ->whereTransferStatus('pending')->update([
                'transfer_id' => $Transfer->id,
                'transfer_status' =>'completed',
                'transfer_date' =>now(),
            ]);
       return $this->returnSuccess('تم عمليه التحويل بنجاح');
    }
}
