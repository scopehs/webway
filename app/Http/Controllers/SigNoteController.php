<?php

namespace App\Http\Controllers;

use App\Events\MappingUpdate;
use App\Models\SigNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use utils\Helper\Helper;

class SigNoteController extends Controller
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
        $new = new SigNote();
        $new->user_id = Auth::id();
        $new->signature_id = $request->signature_id;
        $new->connection_id = $request->connection_id;
        $new->text = $request->text;
        $new->system_id = $request->system_id;
        $new->log_helper = 42;
        $new->save();

        $message = Helper::trackingSig($request->signature_id);
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $message->system_id,
        ]);
        broadcast(new MappingUpdate($flag));
    }

    public function getSigNotesBySystem($id)
    {
        $sigNotes = SigNote::where('system_id', $id)->with([
            'user:id,name',
            'sig:id,signature_id',
        ])->get();

        return ['sigNotes' => $sigNotes];
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
        $note = SigNote::where('id', $id)->first();

        activity()->withoutLogs(function () use ($note) {
            $note->log_helper = 43;
            $note->save();
        });
        $sigID = $note->signature_id;
        $note->delete();
        $message = Helper::trackingSig($sigID);
        $systemID = $message->system_id;
        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'system_id' => $systemID,
        ]);
        broadcast(new MappingUpdate($flag));
    }
}
