<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

    /**
    * @OA\Info(
    *      version="1.0.0",
    *      title="Documentação API Trouw",
    *      description="Documentação desenvolvida para direcionar o desenvolvimento da aplicação Trouw mobile, nela se encontram todos os endpoints
         necessários para o correto funcionamento da aplicação.",
    *      @OA\Contact(
    *          email="contato@fabrika.com"
    *      ),
    * )
    *
    * @OA\Server(
    *      url=L5_SWAGGER_CONST_HOST,
    *      description="Local"
    * ),
    *
    * @OA\SecurityScheme(
    *    securityScheme="bearerAuth",
    *    in="header",
    *    name="bearerAuth",
    *    type="http",
    *    scheme="bearer",
    *    bearerFormat="JWT",
    * ),
    *
    */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
