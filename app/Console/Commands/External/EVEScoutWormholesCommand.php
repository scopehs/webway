<?php

namespace App\Console\Commands\External;

use App\Events\MappingUpdate;
use App\Http\Controllers\Helpers\DiscordBot;
use App\Http\Controllers\Helpers\Wormholes;
use App\Jobs\EveScoutJob;
use App\Models\Connections\Connections;
use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use utils\Helper\Helper;

class EVEScoutWormholesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external:evescout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get EVE Scout Wormhole Details (Thera)';

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
        EveScoutJob::dispatch();
        // $client = new \GuzzleHttp\Client();
        // $res = $client->request('GET', 'https://www.eve-scout.com/api/wormholes', []);

        // if ($res->getStatusCode() == 200) {
        //     // do something

        //     $contents = $res->getBody();

        //     $wormholes = json_decode($contents);

        //     # Check it exists
        //     if ($wormholes) {

        //         $source = "eve-scout";
        //         $eveSigs = Signature::where('created_by_name', '=', 'EVE-Scout')->get();
        //         foreach ($eveSigs as $evesig) {

        //             $eveSigSystem = $evesig->system_id;
        //             $eveSigID = $evesig->id;

        //             $flag = collect([
        //                 'flag' => 2,
        //                 'id' => $eveSigID,
        //                 'system_id' => $eveSigSystem
        //             ]);
        //             broadcast(new MappingUpdate($flag));
        //         }

        //         Signature::where('created_by_name', '=', 'EVE-Scout')->delete();
        //         Connections::where('type', 4)->delete();
        //         $eveScoutK162ID = 91;

        //         foreach ($wormholes as $wormhole) {

        //             $wormEol = Carbon::parse($wormhole->wormholeEstimatedEol);
        //             $wormCreatedAt = Carbon::parse($wormhole->createdAt);
        //             $sourceWormType = Wormholes::type($wormhole->sourceWormholeType->name);
        //             $destoWormType = Wormholes::type($wormhole->destinationWormholeType->name);
        //             $wormInfoTypeName = null;
        //             $wormInfoID = null;

        //             switch ($wormhole->wormholeEol) {
        //                 case "stable":
        //                     $life = 2;
        //                     break;

        //                 case "critical":
        //                     $life = 3;
        //                     break;
        //             }

        //             switch ($wormhole->wormholeMass) {
        //                 case "stable":
        //                     $mass = 2;
        //                     break;

        //                 case "critical":
        //                     $mass = 3;
        //                     break;
        //             }

        //             if ($wormhole->wormholeSourceWormholeTypeId == $eveScoutK162ID) {
        //                 $wormInfoTypeName = $wormhole->destinationWormholeType->name;
        //             } else {
        //                 $wormInfoTypeName = $wormhole->sourceWormholeType->name;
        //             }

        //             $wormInfoID = Wormholes::type($wormInfoTypeName);
        //             // dd($wormInfoID);
        //             $wormInfoShipSize = Wormholes::shipSize($wormInfoID);

        //             switch ($wormInfoShipSize) {
        //                 case 5000000:
        //                     $shipSize = 4;
        //                     break;

        //                 case 62000000:
        //                     $shipSize = 3;
        //                     break;

        //                 case 300000000:
        //                     $shipSize = 2;
        //                     break;

        //                 case 375000000:
        //                     $shipSize = 2;
        //                     break;

        //                 case 450000000:
        //                     $shipSize = 2;
        //                     break;

        //                 case 1000000000:
        //                     $shipSize = 1;
        //                     break;

        //                 case 2000000000:
        //                     $shipSize = 1;
        //                     break;

        //                 default:
        //                     $shipSize = null;
        //             }

        //             $sourceSystem = SolarSystem::where('system_id', $wormhole->solarSystemId)->first();
        //             $destoSystem = SolarSystem::where('system_id', $wormhole->destinationSolarSystem->id)->first();

        //             $sourceTypePull = $sourceSystem->systemType()->first();
        //             $destoTypePull = $destoSystem->systemType()->first();

        //             $sourceType = $sourceTypePull->id;
        //             $destoType = $destoTypePull->id;

        //             switch ($sourceType) {
        //                 case 1:
        //                     $sourceLeadsTo = 1;
        //                     break;

        //                 case 2:
        //                     $sourceLeadsTo = 1;
        //                     break;

        //                 case 3:
        //                     $sourceLeadsTo = 1;
        //                     break;

        //                 case 4:
        //                     $sourceLeadsTo = 2;
        //                     break;

        //                 case 5:
        //                     $sourceLeadsTo = 2;
        //                     break;

        //                 case 6:
        //                     $sourceLeadsTo = 3;
        //                     break;
        //                 case 7:
        //                     $sourceLeadsTo = 4;
        //                     break;

        //                 case 8:
        //                     $sourceLeadsTo = 5;
        //                     break;
        //                 case 9:
        //                     $sourceLeadsTo = 6;
        //                     break;

        //                 case 12:
        //                     $sourceLeadsTo = 8;
        //                     break;

        //                 case 25:
        //                     $sourceLeadsTo = 7;
        //                     break;

        //                 default;
        //                     $sourceLeadsTo = 0;
        //                     break;
        //             }

        //             switch ($destoType) {
        //                 case 1:
        //                     $destoLeadsTo = 1;
        //                     break;

        //                 case 2:
        //                     $destoLeadsTo = 1;
        //                     break;

        //                 case 3:
        //                     $destoLeadsTo = 1;
        //                     break;

        //                 case 4:
        //                     $destoLeadsTo = 2;
        //                     break;

        //                 case 5:
        //                     $destoLeadsTo = 2;
        //                     break;

        //                 case 6:
        //                     $destoLeadsTo = 3;
        //                     break;
        //                 case 7:
        //                     $destoLeadsTo = 4;
        //                     break;

        //                 case 8:
        //                     $destoLeadsTo = 5;
        //                     break;
        //                 case 9:
        //                     $destoLeadsTo = 6;
        //                     break;

        //                 case 12:
        //                     $destoLeadsTo = 8;
        //                     break;

        //                 case 25:
        //                     $destoLeadsTo = 7;
        //                     break;

        //                 default;
        //                     $destoLeadsTo = 0;
        //                     break;
        //             }
        //             $connection = Connections::create([
        //                 'source_system_id' => $wormhole->solarSystemId,
        //                 'target_system_id' => $wormhole->destinationSolarSystem->id,
        //                 'type' => 4,
        //                 'delete_flag' => 0,
        //             ]);
        //             $nameID = $wormhole->signatureId . "-" . "evescout";
        //             $sourceSig = Signature::create([
        //                 'signature_id' => $wormhole->signatureId,
        //                 'name_id' => $nameID,
        //                 'system_id' => $wormhole->solarSystemId,
        //                 'type' => $sourceWormType,
        //                 'signature_group_id' => 1,
        //                 'name' =>  'Unstable Wormhole',
        //                 'leads_to' => $wormhole->destinationSolarSystem->id,
        //                 'connection_id' => $connection->id,
        //                 'signal_strength' => 100.00,
        //                 'life_time' => $wormCreatedAt,
        //                 'life_left' => $wormEol,
        //                 'delete' => 0, 'created_by_id' => 1,
        //                 'created_by_name' => "EVE-Scout",
        //                 'wormhole_info_ship_size_id' => $shipSize,
        //                 'wormhole_info_leads_to_id' =>  $sourceLeadsTo,
        //                 'wormhole_info_mass_id' => $mass,
        //                 'wormhole_info_time_till_death_id' => $life,
        //             ]);

        //             $nameID = $wormhole->wormholeDestinationSignatureId . "-" . "evescout";
        //             $destoSig = Signature::create([
        //                 'signature_id' => $wormhole->wormholeDestinationSignatureId,
        //                 'system_id' => $wormhole->destinationSolarSystem->id,
        //                 'type' => $destoWormType,
        //                 'name_id' => $nameID,
        //                 'signature_group_id' => 1,
        //                 'name' =>  'Unstable Wormhole',
        //                 'leads_to' => $wormhole->sourceSolarSystem->id,
        //                 'connection_id' => $connection->id,
        //                 'signal_strength' => 100.00,
        //                 'life_time' => $wormCreatedAt,
        //                 'life_left' => $wormEol,
        //                 'delete' => 0, 'created_by_id' => 1,
        //                 'created_by_name' => "EVE-Scout",
        //                 'wormhole_info_ship_size_id' => $shipSize,
        //                 'wormhole_info_leads_to_id' => $destoLeadsTo,
        //                 'wormhole_info_mass_id' => $mass,
        //                 'wormhole_info_time_till_death_id' => $life,
        //             ]);

        //             $connection->update([
        //                 'source_sig_id' => $sourceSig->id,
        //                 'target_sig_id' => $destoSig->id,
        //             ]);

        //             $message = Helper::trackingSig($sourceSig->id);
        //             $flag = collect([
        //                 'flag' => 1,
        //                 'message' => $message,
        //                 'system_id' => $wormhole->solarSystemId
        //             ]);
        //             broadcast(new MappingUpdate($flag));

        //             $message = Helper::trackingSig($destoSig->id);
        //             $flag = collect([
        //                 'flag' => 1,
        //                 'message' => $message,
        //                 'system_id' => $wormhole->wormholeDestinationSolarSystemId
        //             ]);
        //             broadcast(new MappingUpdate($flag));
        //         }
        //     }
        // }

        // $webhook = 'https://discord.com/api/webhooks/896490923544956958/NCwWo7lJIxU5Ol0uEJXI6WuU85-peODaiRYba2zGhLCFqewHhuXwBabICSPKYeCDPkBq';

        // # Header
        // $content = 'EVE SCOUT';

        // # Body
        // /*
        //  *  'content' => "Message here.",
        //  *   'embeds' => [
        //  *       [
        //  *           'title' => "An awesome new notification!",
        //  *           'description' => "Discord Webhooks are great!",
        //  *           'color' => '7506394',
        //  *       ]
        // */

        // $embeds = [
        //     'title'         => 'EVE YO YO',
        //     'description'   => 'dirty boy',
        //     'color'         => '7506394',
        // ];

        // DiscordBot::post($webhook, $content, $embeds);
    }
}
