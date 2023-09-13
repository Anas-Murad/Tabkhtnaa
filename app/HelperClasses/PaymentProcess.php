<?php
namespace App\HelperClasses;
use App\Models\Transaction;

class PaymentProcess
{
    public function StartPayment(Transaction &$transaction){
        $transaction->update([
            'payment_id'=>rand(1000000000,99999999999).str()->random(10),
        ]);
        return true;
    }

}
