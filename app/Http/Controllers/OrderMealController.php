<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderMealRequest;
use App\Models\OrderMeal;

class OrderMealController extends Controller
{
    public function index()
    {
        return OrderMeal::all();
    }

    public function store(OrderMealRequest $request)
    {
        return OrderMeal::create($request->validated());
    }

    public function show(OrderMeal $orderMeal)
    {
        return $orderMeal;
    }

    public function update(OrderMealRequest $request, OrderMeal $orderMeal)
    {
        $orderMeal->update($request->validated());

        return $orderMeal;
    }

    public function destroy(OrderMeal $orderMeal)
    {
        $orderMeal->delete();

        return response()->json();
    }
}
