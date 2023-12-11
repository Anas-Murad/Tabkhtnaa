<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CountryDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {

//        return City::first() ;
        return (new CountryDataTable())
            ->render('admin.countries.index'  );
    }
}
