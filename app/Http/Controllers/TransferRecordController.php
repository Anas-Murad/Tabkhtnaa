<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRecordRequest;
use App\Models\TransferRecord;

class TransferRecordController extends Controller
{
    public function index()
    {
        return TransferRecord::all();
    }

    public function store(TransferRecordRequest $request)
    {
        return TransferRecord::create($request->validated());
    }

    public function show(TransferRecord $transferRecord)
    {
        return $transferRecord;
    }

    public function update(TransferRecordRequest $request, TransferRecord $transferRecord)
    {
        $transferRecord->update($request->validated());

        return $transferRecord;
    }

    public function destroy(TransferRecord $transferRecord)
    {
        $transferRecord->delete();

        return response()->json();
    }
}
