<?php

namespace App\Http\Controllers;

use App\Events\MappingUpdate;
use App\Models\SystemNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemNoteController extends Controller
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
        $new = new SystemNote();
        $new->user_id = Auth::id();
        $new->system_id = $request->system_id;
        $new->text = $request->text;
        $new->log_helper = 40;
        $new->save();

        $flag = collect([
            'flag' => 6,
            'system_id' => $request->system_id,
        ]);
        broadcast(new MappingUpdate($flag));
    }

    public function showBySystems($id)
    {
        $notes = SystemNote::where('system_id', $id)->with([
            'user:id,name',
        ])->get();

        return [
            'systemNotes' => $notes,
        ];
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
        $note = SystemNote::where('id', $id)->first();

        activity()->withoutLogs(function () use ($note) {
            $note->log_helper = 41;
            $note->save();
        });
        $flag = collect([
            'flag' => 6,
            'system_id' => $note->system_id,
        ]);
        $note->delete();
        broadcast(new MappingUpdate($flag));
    }
}
