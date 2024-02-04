<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use App\Models\Profile;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $wallets = QueryBuilder::for(Wallet::class)
            ->whereProfileId($request->profile->id)
            ->paginate();

        return WalletResource::collection($wallets);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
