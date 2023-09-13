<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Models\Transfer;

class TransferController extends Controller
{
    public function index()
    {
        return Transfer::all();
    }

    public function store(TransferRequest $request)
    {
        return Transfer::create($request->validated());
    }

    public function show(Transfer $transfer)
    {
        return $transfer;
    }

    public function update(TransferRequest $request, Transfer $transfer)
    {
        $transfer->update($request->validated());

        return $transfer;
    }

    public function destroy(Transfer $transfer)
    {
        $transfer->delete();

        return response()->json();
    }
}
