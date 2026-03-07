<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CityDataTable;
use App\DataTables\CountryDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use HelperTrait;

    public function destroy($country_id, $city_id)
    {
        City::find($city_id)->delete();
        return $this->returnSuccess('تم الحذف بنجاح');
    }

    public function update(Request $request, $country_id, $city_id)
    {

        $data = $request->only(
            'name',
            'country_code',
            'iso2',
            'latitude',
            'longitude',
            'flag',
        );
        $data['country_id'] = $country_id;
        City:: whereId($city_id)->update($data);
        session()->flash('Success', 'تم التعديل  بنجاح ');
        return redirect()->back();

    }

    public function store(Request $request, $country_id)
    {

        $data = $request->only(
            'name',
            'country_code',
            'iso2',
            'latitude',
            'longitude',
            'flag',
        );
        $data['country_id'] = $country_id;
        City::create($data);


        session()->flash('Success', 'تم الاضافه  بنجاح ');
        return redirect()->back();

        return $this->returnSuccess('تم الحذف بنجاح');
    }


    public function edit($country_id, $city_id)
    {
        $city = City::find($city_id);
        return view('admin.countries.cities.create', compact(
            'country_id',
            'city_id',
            'city',
        ));
    }


    public function create ($country_id)
    {
        $country= Country::findOrFail($country_id) ;
        return view('admin.countries.cities.create', compact(
            'country_id',
            'country',
        ));
    }
    public function index($country_id)
    {


        return (new CityDataTable($country_id))
            ->render('admin.countries.cities.index' , [
                'country'=>Country::findOrFail($country_id)
            ]);
    }
}
