<?php

namespace App\Http\Controllers;

use App\Events\RoomsUpdate;
use App\Events\RoomUpdate;
use App\Models\SupportChat;
use App\Models\SupportRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportChatcontroller extends Controller
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
    public function store(Request $request, $id)
    {
        $room = SupportRoom::whereId($id)->first();
        $roomID = $room->id;
        $readByUser = 0;
        $readByWebway = 0;
        if ($room->open_by_user) {
            $readByUser = 1;
        }
        if ($room->open_by_webway) {
            $readByWebway = 1;
        }
        $new = new SupportChat();
        $new->room_id = $roomID;
        $new->user_id = Auth::id();
        $new->message = $request->message;
        $new->read_by_user = $readByUser;
        $new->read_by_webway = $readByWebway;
        $new->save();
        $message = SupportChat::whereId($new->id)->with(['user:id,character_id,main_character_id,name'])->first();


        $flag = collect([
            'flag' => 1,
            'message' => $message,
            'room_id' => $roomID
        ]);
        broadcast(new RoomUpdate($flag));
        $flag = collect([
            'flag' => 2,
            'message' => $message,
            'room_id' => $roomID
        ]);
        broadcast(new RoomsUpdate($flag));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function clearMessages($id)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            SupportRoom::whereId($id)->update(['open_by_webway' => 1]);
            SupportChat::whereRoomId($id)->update(['read_by_webway' => 1]);
        } else {
            SupportRoom::whereId($id)->update(['open_by_user' => 1]);
            SupportChat::whereRoomId($id)->update(['read_by_user' => 1]);
        }
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
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
