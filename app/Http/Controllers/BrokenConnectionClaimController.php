<?php

namespace App\Http\Controllers;

use App\Events\BrokenUpdate;
use App\Models\BrokenConnectionClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrokenConnectionClaimController extends Controller
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
    public function store($id)
    {
        $new = new BrokenConnectionClaim();
        $new->user_id = Auth::id();
        $new->signature_id = $id;
        $new->save();

        $message = soloBroken($id);
        $flag = collect([
            'flag' => 3,
            'message' => $message
        ]);
        broadcast(new BrokenUpdate($flag));
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
        $claim = BrokenConnectionClaim::whereId($id)->first();
        $sigID = $claim->signature_id;
        $claim->delete();

        $message = soloBroken($sigID);
        $flag = collect([
            'flag' => 3,
            'message' => $message
        ]);
        broadcast(new BrokenUpdate($flag));
    }
}
