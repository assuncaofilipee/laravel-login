<?php

namespace App\Swagger\Api;

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
    *      url="",
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
class BasicInfo extends BaseController {}
