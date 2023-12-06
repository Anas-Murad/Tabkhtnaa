<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EvaluationDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {

        $user_id = null;
        if ($request->user_id)
            $user_id = $request->user_id;
        return (new EvaluationDataTable($user_id))->render('admin.evaluation.index'  , compact('user_id'));
    }
}
