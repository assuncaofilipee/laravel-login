<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

    /**
    * @OA\Info(
    *      version="1.0.0",
    *      title="Documentação API controle de acesso Laravel",
    *      description="Documentação com todos os endpoints necessários para a correta utilização da aplicação.",
    *      @OA\Contact(
    *          email="assuncaofilipe97@gmail.com"
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
