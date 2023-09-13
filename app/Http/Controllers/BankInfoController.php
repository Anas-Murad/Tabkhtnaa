<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankInfoRequest;
use App\Http\Requests\UpdateBankInfoRequest;
use App\Models\BankInfo;

class BankInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreBankInfoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankInfoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankInfo  $bankInfo
     * @return \Illuminate\Http\Response
     */
    public function show(BankInfo $bankInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankInfo  $bankInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(BankInfo $bankInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBankInfoRequest  $request
     * @param  \App\Models\BankInfo  $bankInfo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankInfoRequest $request, BankInfo $bankInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankInfo  $bankInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankInfo $bankInfo)
    {
        //
    }
}
