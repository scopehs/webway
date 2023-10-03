<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Honeycomb API Documentation",
 *      description="API Documentation",
 *      @OA\Contact(
 *          email="scopehone@goonswarm.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Honeycomb API Server"
 * )

 *
 * @OA\Tag(
 *     name="Universe",
 *     description="API Endpoints of Universe"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests;use DispatchesJobs;use ValidatesRequests;
}
