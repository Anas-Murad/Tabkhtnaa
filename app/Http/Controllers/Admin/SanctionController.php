<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SanctionsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sanction\CreateSanctionRequest;
use App\Models\Admin;
use App\Models\Sanction;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;

class SanctionController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SanctionsDataTable $dataTable)
    {
        $admins = Admin::where('account_status' , 'active')->get();
        return $dataTable->render('admin.sanctions.index' , compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSanctionRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->saveImage($request->photo, 'uploads/sanction');
        }
        $sanction = Sanction::create($data);
        if ($sanction)
        {
            session()->flash('Success' , 'تم الاضافة  بنجاح ');
            return redirect()->back();
        }else{
            session()->flash('Error' , 'error');
            return redirect()->back();
           }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
