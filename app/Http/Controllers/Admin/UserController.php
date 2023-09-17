<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;

class UserController extends Controller
{



    public function index(UsersDataTable $dataTable)
    {

        return $dataTable->render('admin.users.index');
    }


}
