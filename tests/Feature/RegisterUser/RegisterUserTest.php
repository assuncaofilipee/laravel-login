<?php

namespace Tests\Feature\RegisterUser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     */
    public function shouldRegisterUser()
    {
        $response = $this->post('/app/user/register', [
            "email" => "testuser@gmail.com",
            "email_confirmation" => "testuser@gmail.com",
            "password" => "123456ff",
            "password_confirmation" => "123456ff",
            "terms_of_use" => "true"
        ]);
        $response->assertJsonFragment(["email" => "testuser@gmail.com" ]);
        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function shoudNotRegisterUser()
    {
        $response = $this->post('/app/user/register');
        $response->assertJson(
            [
                "success" => "false",
                "data" => [
                      "email" => [
                         "O campo email é obrigatório."
                      ],
                      "password" => [
                            "O campo senha é obrigatório."
                      ],
                      "terms_of_use" => [
                        "O campo termos de uso é obrigatório."
                     ]
                   ]
             ]);
        $response->assertStatus(422);
    }

        /**
     * @test
     */
    public function shoudNotRegisterUserAndReturnAllOthersErrors()
    {
        $response = $this->post('/app/user/register',
        ['email' => 'hakuna.com', "password" => '123', 'terms_of_use' => false]);
        $response->assertJson(
            [
                "success" => "false",
                "data" => [
                      "email" => [
                         "O campo email deve ser um endereço de e-mail válido.",
                         "O campo email de confirmação não confere."
                      ],
                      "password" => [
                            "O campo senha de confirmação não confere.",
                            "O campo senha deve ter pelo menos 8 caracteres.",
                            "O campo senha deve conter pelo menos uma letra."
                      ],
                      "terms_of_use" => [
                        "É obrigatório o aceite dos Termos de uso",
                     ]
                   ]
             ]);
        $response->assertStatus(422);
    }
}
