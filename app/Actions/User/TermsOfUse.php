<?php

namespace App\Actions\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class TermsOfUse extends Controller
{
   /**
    *
     * @OA\Get(
     *   tags={"Acesso"},
     *   summary="Obter Termos de uso",
     *   description="Retorna o texto do Termos de uso",
     *   path="/app/terms-of-use",
     *   @OA\Response(
     *      response=200,
     *      description="Termos de uso",
     *      @OA\JsonContent(
     *        @OA\Property(property="success", type="string"),
     *        @OA\Property(property="data", type="object",
     *            @OA\Property(property="terms_of_use", type="string"),
     *        ),
     *      example={
     *          "success" : "true",
     *          "data" : {
     *                       "terms_of_use": "Termos de uso do aplicativo
                                              Última modificação: 10/02/2022
                                              Bem-vindo ao Trouw
                                              Agradecemos por usar nossos produtos e serviços!
                                              Continua...",
     *                   }
     *      },
     *       ),
     *    ),
     * )
    *
    */
    public function termsOfUse()
    {
        File::get(storage_path('termsOfUse.txt'));
        return response()->json([
            'success' => 'true',
            'data' => [
                'terms_of_use' => File::get(storage_path('termsOfUse.txt'))
            ]
        ]);
    }
}
