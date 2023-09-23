<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TranslateDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Translate\CreateRequest;
use App\Http\Requests\Admin\Translate\UpdateRequest;
use App\Models\Translate;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(TranslateDataTable $dataTable)
    {
        return $dataTable->render('admin.translations.index');
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
    public function store(CreateRequest $request)
    {
        $data = $request->all();
        $translate =Translate::create($data);
        if ($translate)
        {
            session()->flash('Success' , 'تم الاضافة  بنجاح ');
            return response()->json(['success' => true]);
        }else
            session()->flash('Error' , 'error');
            return response()->json(['success' => false]);
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        $translate = Translate::findOrFail($data['id']);
        $translate->update($data);
        session()->flash('Success' , 'تم تعديل  بنجاح ');
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $translate = Translate::findOrFail($id);
        $translate->delete();
        return response()->json(['success' => true]);
    }
}
