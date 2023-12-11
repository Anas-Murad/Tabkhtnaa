<?php

namespace App\Http\Controllers;

use App\HelperClasses\PaymentProcess;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransferRecord;
use App\Models\UserDriverCash;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::all();
    }

    public function store( $dataArray)
    {
        $transaction =  Transaction::create( $dataArray ) ;
        if ($dataArray['payment_method'] != 'cash'){
            $StartPayment= (new PaymentProcess)->StartPayment($transaction);
        }
        return $transaction;
    }




    public function show(Transaction $transaction)
    {
        return $transaction;
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'user_id' => ['required'],
            'order_id' => ['required'],
            'payment_id' => ['required'],
            'payment_method' => ['required'],
            'service_type' => ['required'],
            'amount' => ['required'],
            'currency' => ['required'],
            'status' => ['required'],
            'admin_status' => ['required'],
            'admin_notes' => ['required'],
            'tracking_data' => ['required'],
            'webhook' => ['required'],
        ]);

        $transaction->update($request->validated());

        return $transaction;
    }



    public function distribution( Order $order)
    {

        $delivery_fees =$order->delivery_fees;
        $discount =$order->discount;
        $tax =$order->tax;
        $sub_total =$order->sub_total;
        $total =$order->total;

        $transaction = $order->loadMissing('Transaction')->Transaction;
        $delivery_id =$order->delivery_id;



        $chef_id=$order->chef_id;

        $chef_amount = $sub_total - $discount ; // نسبه الطاهي
        $admin_amount = $chef_amount * 0.20; //نسبه الادمن من الطلبيه
        $admin_delivery_fees = $delivery_fees * 0.20; //نسبه الادمن من التوصيل
        $delivery_amount = $delivery_fees - $admin_delivery_fees ; // نسبه الموصل

        $total_cash = ($total + $tax ) - $delivery_amount ; //  الكاش الي لازم يتجول للادمن
        if ($order->payment_method == 'cash'){

              $user_driver_cash=  UserDriverCash::create([
                   'order_id' => $order->id,
                   'user_id' => $delivery_id,
                   'total_cash' => $total_cash,
                   'status' => 'pending',
                ]);

        }


        TransferRecord::create([
            'to_type' =>'delivery',
            'to_id' =>$delivery_id,
            'order_id'=>$order->id,
            'amount'=>$delivery_amount,
            'transaction_id'=>$transaction->id,
            'percent'=>0.20,
            'user_driver_cash_id' =>isset($user_driver_cash) ? $user_driver_cash->id : null,
            'transfer_status'=>isset($user_driver_cash) ? 'completed' : 'pending', // enum('pending', 'completed')
            'transfer_date'=>isset($user_driver_cash) ? now(): null,
        ]) ;
        TransferRecord::create([
            'to_type' =>'chef',
            'to_id' =>$chef_id,
            'order_id'=>$order->id,
            'amount'=>$chef_amount,
            'transaction_id'=>$transaction->id,
            'percent'=>0.20,
            'user_driver_cash_id' =>isset($user_driver_cash) ? $user_driver_cash->id : null,
            'transfer_status'=> 'pending', // enum('pending', 'completed')
            'transfer_date'=> null,
        ]) ;
        TransferRecord::create([
            'to_type' =>'admin',
            'to_id' =>null,
            'order_id'=>$order->id,
            'amount'=>$admin_amount + $admin_delivery_fees ,
            'transaction_id'=>$transaction->id,
            'percent'=>null,
            'user_driver_cash_id' =>isset($user_driver_cash) ? $user_driver_cash->id : null,
            'transfer_status'=>!isset($user_driver_cash) ? 'completed' : 'pending', // enum('pending', 'completed')
            'transfer_date'=>!isset($user_driver_cash) ? now(): null,
        ]) ;




    }
}
