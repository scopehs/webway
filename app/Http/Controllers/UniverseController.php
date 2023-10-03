<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 180);

use App\Models\SDE\SolarSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class UniverseController extends Controller
{
    use HasRoles;
    use HasPermissions;

    /**
     * @OA\Get(
     *      path="/api/solar_system/{system_id}",
     *      operationId="solar_system",
     *      tags={"SolarSystem"},
     *      summary="Gets Solar System Information",
     *      description="Returns Solor System Data",
     *      @OA\Parameter(
     *          name="system_id",
     *          description="Solar System ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function solar_system($system_id)
    {
        $endpoint = '/api/solar_system/'.$system_id;

        if (is_numeric($system_id)) {
            $response = SolarSystem::where('system_id', $system_id)
                ->with('constellation')
                ->with('region')
                ->with('planets')
                ->with('moons')
                ->with('star')
                ->with('statics')
                ->first();
        } else {
            $response = SolarSystem::where('name', $system_id)
                ->with('constellation')
                ->with('region')
                ->with('planets')
                ->with('moons')
                ->with('star')
                ->with('statics')
                ->first();
        }

        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json('Not Found', 404);
        }
    }

    public function allSystems()
    {
        $check = Auth::user();
        $check->hasPermissionTo('super_admin');
        if ($check) {
            $data = SolarSystem::with(['statics', 'systemType', 'star', 'constellation', 'region', 'signatures', 'systemType'])->get();

            return ['universeList' => $data];
        }
    }
}
