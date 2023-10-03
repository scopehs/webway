<?php

use App\Http\Controllers\AllTheStatsController;
use App\Http\Controllers\Api\CalculateJumpDistanceController;
use App\Http\Controllers\Api\importGoonJumpBridges;
use App\Http\Controllers\Api\Routing;
use App\Http\Controllers\Api\setCharacterWayPointController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrokenConnectionClaimController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharTrackingController;
use App\Http\Controllers\ConnectionsController;
use App\Http\Controllers\EveEsiStatusController;
use App\Http\Controllers\FeedBackController;
use App\Http\Controllers\GasFlavorController;
use App\Http\Controllers\HotAreaController;
use App\Http\Controllers\JoveSystemController;
use App\Http\Controllers\MetoCookiesController;
use App\Http\Controllers\NebulaController;
use App\Http\Controllers\ParseController;
use App\Http\Controllers\ParseShowInfoWindowController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SavedRouteController;
use App\Http\Controllers\SavedRouteUserController;
use App\Http\Controllers\ShortestPathController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SigNoteController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\SolarSystemController;
use App\Http\Controllers\SupportChatcontroller;
use App\Http\Controllers\SupportRoomController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\SystemNoteController;
use App\Http\Controllers\testController;
use App\Http\Controllers\UniverseController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WormholeStaticsController;
use App\Http\Controllers\WormholeTypeController;
use App\Models\BrokenStaticClaim;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which vvfvvv
| is assigned the "api" middleware group. Enjoy building your API!
|ffff
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/timers','TimerController@getTimerData');

// Route::middleware('ffffauth:api')->get('/notifications', function (Request $request) {
//     return $request->notifications();sssssssffffff
// });
// Route::get('/notifications','NotificationRecordddddddsController@index');dddd

// Signatures

//@scopeh this just grabs all non-deleted sigs for a given system
//@scopeh this is to update a sig to deleted then boradcasts it to everyone in that system

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/import/jump_bridges', [importGoonJumpBridges::class, 'store']);
    Route::post('/setwaypoint', [setCharacterWayPointController::class, 'set_way_point']);
    Route::post('/path', [Routing::class, 'path']);
    Route::post('/signature/post', [ParseController::class, 'post']);
    Route::post('/parse_info/{id}', [ParseShowInfoWindowController::class, 'parse_info']);
    Route::get('/getlocationstatus', [EveEsiStatusController::class, 'show']);
    Route::get('/getgasinfo', [GasFlavorController::class, 'index']);
    Route::post('/getdcanlocation', [AuthController::class, 'getDscanLocation']);

    Route::controller(testController::class)->group(function () {
        Route::get('testgetsystemnotes/{id}', 'getSystemNotes');
        Route::get('testgetlocation/{charid}', 'getTrackingInfo');
    });

    Route::controller(RouteController::class)->group(function () {
        Route::post('/evestuff', 'eveStuff');
    });

    Route::controller(SupportRoomController::class)->group(function () {
        Route::get('/support/rooms', 'index');
        Route::get('/support/room/{id}', 'show');
        Route::post('/support/makeroom/{id}', 'store');
        Route::post('/support/closeroom/{id}', 'close');
        Route::delete('/support/delete/{id}', 'destroy');
    });

    Route::controller(SupportChatcontroller::class)->group(function () {
        Route::post('/support/message/{id}', 'store');
        Route::post('/support/messageclear/{id}', 'clearMessages');
    });

    Route::controller(AppController::class)->group(function () {
        Route::get('/geturl', 'siteUrl');
        Route::post('/updateoverlay/{state}', 'updateOverLay');
        Route::post('/refreshoverlay', 'refreshOverLay');
    });

    Route::controller(WormholeStaticsController::class)->group(function () {
        Route::get('/static/static', 'index');
    });

    Route::controller(BrokenStaticClaim::class)->group(function () {
        Route::get('/static/claim/{id}', 'store');
    });

    Route::controller(BrokenConnectionClaimController::class)->group(function () {
        Route::post('/broken/claim/{id}', 'store');
        Route::delete('/broken/removeclaim/{id}', 'destroy');
    });






    Route::controller(UserController::class)->group(function () {
        Route::get('/location/{id}', 'showLocation');
        Route::get('/userlogs/{id}', 'logs');
        Route::get('/alluserlogsCount', 'allLogsCount');
        Route::get('/userlist', 'index');
        Route::get('/fulluserlist', 'fullUserList');

        Route::get('/activiytypes', 'descriptionTypesList');
        Route::post('/checkadblock', 'checkAd');
        Route::post('/usercharupdate/{id}', 'updateChar');
        Route::post('/alluserlogs/{from}/{to}', 'allLogs');
        Route::put('/byebye', 'byebye');
    });

    Route::controller(CalculateJumpDistanceController::class)->group(function () {
        Route::post('/jump/distance', 'calculate_jump_distance');
        Route::post('/jump/distance/build', 'build_graph');
    });

    Route::controller(UniverseController::class)->group(function () {
        Route::get('/solar_system/{system_id}', 'solar_system');
        Route::get('/unilist', 'allSystems');
    });

    Route::controller(SignatureController::class)->group(function () {
        Route::get('/sigs/{id}', 'show');
        Route::get('/getsigs', 'index');
        Route::post('/sigssdelete/{id}', 'softDelete');
        Route::post('/sigdone/{id}', 'sigDone');
        Route::post('/sigreport/{id}', 'sigReport');
        Route::post('/addsigid', 'addSigID');
        Route::post('/sigupdate/{id}', 'update');
        Route::post('/adddrifter/{id}', 'addDrift');
        Route::post('/whaledriftadd', 'addWhaleSig');
        Route::post('/jovesystemclearSigs/{systemID}', 'clearWhaleSigs');
        Route::post('/siguser', 'addUser');
        Route::delete('/siguser/{id}', 'removeUser');
        Route::get('/broken', 'getBrokenConnections');
    });


    Route::controller(MetoCookiesController::class)->group(function () {
        Route::get('/metrogo', 'pull');
        Route::post('/metrocookie', 'store');
    });

    Route::controller(SystemController::class)->group(function () {
        Route::get('systemlist', 'list');
        Route::get('/titanlist', 'titanList');
        Route::get('/regionlist', 'regionList');
        Route::get('/constellationlist', 'ConstellationList');
    });

    Route::controller(WormholeTypeController::class)->group(function () {
        Route::get('/wormholetypelist/{id}', 'list');
        Route::get('/driftertypelist', 'drifterList');
    });

    Route::controller(CharacterController::class)->group(function () {
        Route::get('/charsbyuser/{id}', 'getAllByUserId');
        Route::get('/charlist', 'list');
        Route::post('/updatetrackingchar/{id}', 'tracking');
        Route::post('/entertracking/{id}', 'enterTracking');
    });

    Route::controller(CharTrackingController::class)->group(function () {
        Route::get('/routemapping/{charID}', 'mapping');
        Route::get('/getlocationinfo/{charid}', 'getTrackingInfo');
        Route::get('/getlocationinfobytrackid/{trackID}', 'getTrackingInfoByTrackID');
        Route::put('/updatelocationtest', 'test');
        Route::post('/routeadd', 'store');
    });

    Route::controller(ConnectionsController::class)->group(function () {
        Route::get('/connections', 'index');
        Route::get('/connectionlists', 'list');
        Route::get('/reservedconnection', 'getReservedConnections');
        Route::get('/getconnectionNotes/{id}', 'getNotesByConnectionID');
        Route::get('/reportConnection/{id}', 'reportConnection');
        Route::post('/leadsto', 'addConnection');
        Route::post('/reserveconnection/{id}', 'reserveConnection');
        Route::post('/removereservedconnection/{id}', 'removeReserveFromConnection');
        Route::post('/addconnectionnote', 'addConnectionNote');
        Route::delete('/deleteconnectionnote/{id}', 'deleteConnectionNotes');
        Route::delete('/deleteConnection/{id}', 'deleteConnection');
    });


    Route::controller(FeedBackController::class)->group(function () {
        Route::get('/feedback', 'index');
        Route::post('/feedback', 'store');
        Route::delete('/feedback/{id}', 'destroy');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles', 'index');
        Route::get('/allusersroles', 'getAllUsersRoles');
        Route::put('/rolesadd', 'addRole');
        Route::put('/rolesremove', 'removeRole');
    });

    Route::controller(AllTheStatsController::class)->group(function () {
        Route::get('/allthestats', 'allTheStatsUserCurrent');
        Route::get('/allthestatslastmonth', 'lastMonth');
    });

    Route::controller(HotAreaController::class)->group(function () {
        Route::get('/hotarea', 'index');
        Route::post('/hotarea', 'store');
        Route::delete('/hotarea/{id}', 'destroy');
    });

    Route::controller(SavedRouteController::class)->group(function () {
        Route::get('/savedroute/{link}', 'getRouteLink');
        Route::post('/loadroute', 'loadRoute');
        Route::delete('/deletesaveroute', 'destroy');
    });

    Route::controller(SavedRouteUserController::class)->group(function () {
        Route::get('/savedroutes', 'getByUserID');
        Route::post('/saveroute', 'store');
    });

    Route::controller(SolarSystemController::class)->group(function () {
        Route::get('/currentsystemchars/{system_id}', 'charCount');
        Route::get('/lastsystemchars/{system_id}', 'charCount');
    });

    Route::controller(JoveSystemController::class)->group(function () {
        Route::get('/jovesystems', 'systemsWithDrifter');
        Route::get('/joveregionlist', 'regionList');
        Route::post('/jovesystemlastupdated/{id}', 'lastChecked');
        Route::post('mainjovesystemno/{id}', 'noDrfiterUpdateMain');
        Route::post('/addjove/{id}', 'store');
    });

    Route::controller(UserActivityController::class)->group(function () {
        Route::get('/usercount', 'index');
        Route::get('/chartdatahour', 'SigsAddedHour');
        Route::post('/chartdatamins', 'SigsAddedMins');
    });

    Route::controller(SystemNoteController::class)->group(function () {
        Route::get('/getsystemnotes/{id}', 'showBySystems');
        Route::post('/addsystemnotes', 'store');
        Route::delete('/deletesystemnotes/{id}', 'destroy');
    });

    Route::controller(SigNoteController::class)->group(function () {
        Route::get('/getsignotes/{id}', 'getSigNotesBySystem');
        Route::post('/addsignote', 'store');
        Route::delete('/deletesignote/{id}', 'destroy');
    });

    Route::controller(SiteSettingController::class)->group(function () {
        Route::get('/setSigShow', 'getSigShow');
        Route::get('/paygas', 'getPaygas');
        Route::get('/paygasamount', 'getPayGasAmount');
        Route::post('/setSigShow/{state}', 'setSigShow');
        Route::post('/paygas/{state}', 'setPaygas');
        Route::post('/paygasamount/{amount}', 'setPayGasAmount');
    });

    Route::controller(NebulaController::class)->group(function () {
        Route::get('/nebula', 'getJabber');
        Route::get('/nebulalist', 'nebualList');
        Route::post('/nebula/{id}', 'updateJabber');
    });

    Route::controller(ShortestPathController::class)->group(function () {
        Route::get('/shortest', 'index');
        Route::get('/shortest/{id}', 'show');
        Route::post('/shorted', 'store');
        Route::delete('/shortest/{id}', 'destroy');
    });


    Route::get('/user/info', [AuthController::class, 'loginInfo']);
});
