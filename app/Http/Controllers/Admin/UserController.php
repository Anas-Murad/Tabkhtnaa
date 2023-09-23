<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Storage;
use App\DataTables\UsersDataTable;

class UserController extends Controller
{
    use HelperTrait;

    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }
}
