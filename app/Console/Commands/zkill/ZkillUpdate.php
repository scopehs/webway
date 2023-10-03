<?php

namespace App\Console\Commands\zkill;

use App\Http\Controllers\Helpers\DiscordBot;
use App\Models\Zkill;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Console\Command;

class ZkillUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:zkill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will pull and add kills from zkill every 60secs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = 'https://redisq.zkillboard.com/listen.php';
        $systemLists = collect([]);

        $client = new GuzzleHttpClient();
        $headers = [];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);
        $kills = Utils::jsonDecode($response->getBody(), true);

        foreach ($kills as $kill) {
            foreach ($kill['killmail']['attackers'] as $attacker) {
                if ($attacker['final_blow'] == true) {
                    $attackers_alliance_id = $attacker['alliance_id'] ?? null;
                    $attackers_character_id = $attacker['character_id'];
                    $attackers_corporation_id = $attacker['corporation_id'];
                    $attackers_ship_type_id = $attacker['ship_type_id'];
                }
            }

            $zkill = new Zkill();
            $zkill->id = $kill['killID'];
            $zkill->attackers_alliance_id = $attackers_alliance_id;
            $zkill->attackers_character_id = $attackers_character_id;
            $zkill->attackers_corporation_id = $attackers_corporation_id;
            $zkill->attackers_ship_type_id = $attackers_ship_type_id;
            $zkill->killmail_time = $kill['killmail']['killmail_time'];
            $zkill->victim_alliance_id = $kill['killmail']['victim']['alliance_id'] ?? null;
            $zkill->victim_character_id = $kill['killmail']['victim']['character_id'];
            $zkill->victim_corporation_id = $kill['killmail']['victim']['corporation_id'];
            $zkill->victim_ship_type_id = $kill['killmail']['victim']['ship_type_id'];
            $zkill->solar_system_id = $kill['killmail']['solar_system_id'];
            $zkill->totalValue = $kill['zkb']['totalValue'];
            $zkill->save();

            if (! $systemLists->contains($zkill->solar_system_id)) {
                $systemLists->push($zkill->solar_system_id);
            }
        }

        foreach ($systemLists as $systemID) {
            $kills = Zkill::where('solar_system_id', $systemID)->where('created_at', '>=', Carbon::now()->subDay())->count();

            $flag = collect([
                'flag' => 5,
                'id' => $systemID,
                'kills' => $kills,
            ]);
        }

        $webhook = 'https://discord.com/api/webhooks/896490923544956958/NCwWo7lJIxU5Ol0uEJXI6WuU85-peODaiRYba2zGhLCFqewHhuXwBabICSPKYeCDPkBq';

        // Header
        $content = 'DEATH.';

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

        $embeds = [
            'title'         => 'THEY ALL DIED',
            'description'   => 'dirty boy',
            'color'         => '7506394',
        ];

        DiscordBot::post($webhook, $content, $embeds);
    }
}
