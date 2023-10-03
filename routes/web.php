
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionsController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\JobTestController;
use App\Http\Controllers\MetoCookiesController;
use App\Http\Controllers\Scopeh\ScopehController;
use App\Http\Controllers\testController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a gfefefeferoup which
| contains the "web" middleware group. Now create something great! ffffff
|
*/


Route::controller(ScopehController::class)->group(function () {
    Route::get('/scopeh/discord/test', 'discord');
});

Route::controller(MetoCookiesController::class)->group(function () {
    Route::get('/metrogo', 'pull');
});

Route::controller(testController::class)->group(function () {
    Route::get('/missing', 'getMissingSigs');
    Route::get('/searchuser/{name}', 'getMainCharID');
    Route::get('/charlogtest/{id}', 'charLogs');
    Route::get('/testcharrefresh/{id}', 'testTokenRefresh');
    Route::get('/testeveup', 'testEveUp');
    Route::get('/getlocation/{id}', 'getlocation');
    Route::get('/getlocationJobTest/{id}', 'getlocationJobTest');
    Route::get('/jabberpingtest', 'jabberPingTest');
    Route::get('/listtest', 'lists');
    Route::get('/fixlife', 'fixLife');
    Route::get('/lasturl', 'lasturl');
    Route::get('/hithere', 'horizon');
    Route::get('/hitherealso', 'prequal');
    Route::get('/testlocation/{charid}', 'getTrackingInfo');
    Route::get('/getcurrentstatsall', 'testGetStatsAll');
    Route::get('/testjson', 'testJsonSetting');
    Route::get('/testjabberagain', 'testjabberPingAgain');
    Route::get('/testgas', 'testSeed');
    Route::get('/testmovestats', 'testsnap');
    Route::get('/testlogs', 'allLogs');
    Route::get('/hithereagain', 'logreader');
    Route::get('/zkill', 'zkill');
    Route::get('/test//testShort', 'testShort');
    Route::get('/test/systemstatic', 'getMissingStatics');
    Route::get('/test/populateDoneID', 'populateDoneID');
    Route::get('/test/testsiggone/{id}', 'testSigDelete');
    Route::get('/test/getsystemcount', 'getSystemCount');
    Route::get('/test/testnextsystem/{id}', 'testNextSystemSigs');
    Route::get('/test/testesi', 'testESIHelper');
    Route::get('/test/testwebway/{id}', 'testLocationWebway');
});

Route::controller(UserActivityController::class)->group(function () {
    Route::get('/testchartlogs', 'SigsAddedHour');
});

Route::controller(JobTestController::class)->group(function () {
    Route::get('/testjob/{start}/{end}', 'start');
    Route::get('/metoTestJob', 'getMeto');
    route::get('/testwebway/{start}/{end}', 'returnData');
    Route::get('/dance/{id}', 'testLog');
    Route::get('/dance2', 'testJob');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/getalluserlogs', 'allLogs');
});


Route::controller(DevController::class)->group(function () {
    Route::get('/dev/getstats/{year}/{month}', 'getHistoryStats');
    Route::get('/dev/fixdoneby/{year}/{month}', 'setDoneByOnHistory');
    Route::get('/dev/fixdriftername', 'fixDrifterName');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/73cbd63ecd4d2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'admin');
    ////// Route::get('/7fegrghrthtrhtr2d9267ae4ad7bf25c704/5a1f48be9e4df773064f33590be892ff', 'martyn');
    Route::get('/7fegrghrtht2ff', 'evestuffUser');
    Route::get('/scopeh', 'scopeh');
    Route::get('/monty', 'monty');
    Route::get('/login', 'login')->name('login');
    Route::get('/gice/login', 'redirectToProvider');
    Route::get('/gice/callback', 'handleProviderCallback');
    Route::get('/logout', 'logout');
});

Route::controller(ConnectionsController::class)->group(function () {
    Route::get('fixconnection/{id}', 'fixConnectionHistory');
});




/*
 * ESI Tokens Routes
 */

Route::get('esi/add', [
    'as' => 'esi.add',
    'uses' => 'ESI\ESITokensController@redirectToProvider',
]);

Route::get('esi/callback', 'ESI\ESITokensController@handleProviderCallback');

//  NOTHING BELOW THIS LINEfffff
Route::get('/{any}', 'AppController@index')->where('any', '.*');
