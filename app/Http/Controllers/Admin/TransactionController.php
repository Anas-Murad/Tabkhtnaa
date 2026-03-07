<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TransactionDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function show($id)
    {
        $transaction= Transaction::with([
           'transferRecords.user',
           'user',
        ]) ->findOrFail($id) ;

        return  view('admin.transactions.show'  , compact('transaction')) ;
        return $transaction ;
    }
    public function index($status= null)
    {
        $pageTitle = "الحركات المالية" ;

        switch ($status){
            case 'pending' :
                $pageTitle .= " - قيد المراجعه" ;
                break;
            case 'success' :
                $pageTitle .= " - تم تأكيد الحركة" ;
                break;

            case 'failed' :
                $pageTitle .= " - تم رفض الحركة" ;
                break;
            case 'completed' :
                $pageTitle .= " - مكتملة" ;
                break;
            case 'uncompleted' :
                $pageTitle .= " - غير مكتملة" ;
                break;
                //'pending' , 'success' ,'failed' ,'completed' ,'uncompleted'
        }
        return (new TransactionDataTable( $status))
            ->render('admin.transactions.index'  , compact('pageTitle' , 'status'));


    }
}
