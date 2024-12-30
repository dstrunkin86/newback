<?php

namespace App\Http\Controllers\Admin;

use App\Filters\OrderFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrderFilter $filter)
    {
        if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))){
            return response()->json(['message' => 'Нет доступа'],401);
        }
        return Order::with(['artwork','artwork.artist'])->orderBy('created_at','desc')->filter($filter)->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
