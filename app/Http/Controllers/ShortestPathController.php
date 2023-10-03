<?php

namespace App\Http\Controllers;

use App\Events\ShortestUpdate;
use App\Models\ShortestPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShortestPathController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = allShortest();
        return ['short' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $return =  getShortestRouteStart($request->startSystemID, $request->endSystemID);
        $new = new ShortestPath();
        $new->start_system_id = $request->startSystemID;
        $new->end_system_id = $request->endSystemID;
        $new->link = $return['link'];
        $new->jumps = $return['jumps'];
        $new->notes = $request->notes;
        $new->added_by_id = Auth::id();
        $new->save();

        $message = soloShortest($new->id);

        $flag = collect([
            'flag' => 1,
            'message' => $message
        ]);
        broadcast(new ShortestUpdate($flag));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = soloShortest($id);

        return ['short' => $data];
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
        deleteShortest($id);
        $flag = collect([
            'flag' => 2,
            'message' => $id
        ]);
        broadcast(new ShortestUpdate($flag));
    }
}
