<?php

namespace App\Http\Controllers;

use App\Events\RoomsUpdate;
use App\Events\UserUpdate;
use App\Models\SupportChat;
use App\Models\SupportRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = SupportRoom::with(['user:id,character_id,main_character_id,name', 'messages', 'messages.user:id,character_id,main_character_id,name'])->get();
        return ["rooms" => $rooms];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $new = new SupportRoom();
        $new->user_id = $id;
        $new->save();

        $message = SupportRoom::whereId($new->id)->with(['user:id,character_id,main_character_id,name', 'messages', 'messages.user:id,character_id,main_character_id,name'])->first();
        $flag = collect([
            'flag' => 1,
            'message' => $message,
        ]);

        broadcast(new RoomsUpdate($flag));

        $userID = $id;
        $flag = collect([
            'flag' => 5,
            'user_id' => $userID,
        ]);

        broadcast(new UserUpdate($flag));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = SupportRoom::whereUserId($id)
            ->with([
                'user:id,character_id,main_character_id,name',
                'messages', 'messages.user:id,character_id,main_character_id,name'
            ])->first();
        return ["room" => $room];
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
        $room = SupportRoom::whereId($id)->first();
        $room->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = SupportRoom::whereId($id)->first();
        $userID = $room->user_id;
        $room->delete();
        SupportChat::whereRoomId($id)->delete();


        $flag = collect([
            'flag' => 6,
            'user_id' => $userID,
        ]);

        broadcast(new UserUpdate($flag));
    }

    public function close($id)
    {
        $user = Auth::user();
        if ($user->can('super_admin')) {
            SupportRoom::whereId($id)->update(['open_by_webway' => 0]);
        } else {
            SupportRoom::whereId($id)->update(['open_by_user' => 0]);
        }
    }
}
