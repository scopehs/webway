<?php

namespace App\Http\Controllers;

use App\Events\HotAreaUpdate;
use App\Events\OverlayUpdate;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function getSigShow()
    {
        $num = SiteSetting::where('id', 1)->first();
        $num = $num->settings['show_sig_list'];

        return ['num' => $num];
    }

    public function setSigShow($state)
    {
        SiteSetting::where('id', 1)->update(['settings->show_sig_list' => $state]);
        $message = $state;
        $flag = collect([
            'flag' => 4,
            'message' => $message,
        ]);
        broadcast(new OverlayUpdate($flag));
    }

    public function setPayGas($state)
    {
        SiteSetting::where('id', 1)->update(['settings->pay_gas' => $state]);
        $message = $state;
        $flag = collect([
            'flag' => 2,
            'message' => $message,
        ]);
        broadcast(new HotAreaUpdate($flag));
    }

    public function setPayGasAmount($amount)
    {
        SiteSetting::where('id', 1)->update(['settings->pay_gas_amount' => $amount]);
        $message = $amount;
        $flag = collect([
            'flag' => 3,
            'message' => $message,
        ]);
        broadcast(new HotAreaUpdate($flag));
    }

    public function getPaygas()
    {
        $num = SiteSetting::where('id', 1)->first();
        $num = $num->settings['pay_gas'];

        return ['num' => $num];
    }

    public function getPayGasAmount()
    {
        $num = SiteSetting::where('id', 1)->first();
        $num = $num->settings['pay_gas_amount'];

        return ['num' => $num];
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
