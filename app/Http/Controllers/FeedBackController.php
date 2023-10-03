<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\DiscordBot;
use App\Models\EVE\Characters;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedBackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['feedback' => Feedback::with(['user'])->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feedback = Feedback::create($request->all());

        $this->discord($feedback);
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
        Feedback::where('id', $id)->delete();
    }

    public function discord($request)
    {
        $feedback = Feedback::where('id', $request->id)
            ->with('user')
            ->first();

        $extraText = null;


        if (User::whereId(Auth::id())->pluck('character_id')->first() > 0) {
            $char = Characters::whereId(User::whereId(Auth::id())->pluck('character_id')->first())->first() ?? null;
            $systemName = $char->currentSystem->name ?? null;
            if ($systemName) {
                $extraText = "         (Reported while tracking char is in " . $systemName . ")";
            }
        }

        // Dev this further to allow for webhooks to be added per function.
        // This is the webhook url, it should be hashed in the DB.
        $webhook = 'https://discord.com/api/webhooks/895459274476650536/_IBtb1l80oQt0whUOIoGOj_FGqlVfSuR9zArFshoXwVdY3PyhkKGyVaxvAE3FfU5feOn';

        // Header
        $content = '@everyone - new feedback report.';

        // Body
        /*
         *  'content' => "Message here.",
         *   'embeds' => [
         *       [
         *           'title' => "An awesome new notification!",
         *           'description' => "Discord Webhooks are great!",
         *           'color' => '7506394',
         *       ]
        */

        $text = $feedback->text . $extraText;

        $embeds = [
            'title'         => $feedback->user->name . ' : ' . $feedback->title,
            'description'   => $text,
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
