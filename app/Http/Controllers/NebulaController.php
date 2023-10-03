<?php

namespace App\Http\Controllers;

use App\Events\HotAreaUpdate;
use App\Models\Nebula;
use Illuminate\Http\Request;

class NebulaController extends Controller
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

    public function nebualList()
    {
        $list = Nebula::whereNotNull('id')->where('jabber', 0)->select(['name as text', 'id as value'])->get();

        return ['nebulalist' => $list];
    }

    public function getJabber()
    {
        $data = Nebula::where('jabber', 1)->get();

        return ['hotNebula' => $data];
    }

    public function updateJabber(Request $request, $id)
    {
        $neb = Nebula::where('id', $id)->first();
        $neb->jabber = $request->jabber;
        $neb->save();

        $flag = collect([
            'flag' => 4,
        ]);
        broadcast(new HotAreaUpdate($flag));

        $flag = collect([
            'flag' => 5,
        ]);
        broadcast(new HotAreaUpdate($flag));
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
