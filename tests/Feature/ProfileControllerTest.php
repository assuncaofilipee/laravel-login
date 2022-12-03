<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
	use DatabaseTransactions;

	private $auth;
	private $user;
	private $faker;

	public function setUp(): void
	{
		parent::setUp();

		$this->user = User::factory()->create([
				'password' => Hash::make('123456ff')
			]
		);

		$response = $this->post('/app/login', [
				'email' => $this->user->email,
				'password' => '123456ff'
			]
		);

		$token = $response->json()['data']['access_token'];

		$this->auth = [
			'Authorization' => 'Bearer ' . $token
		];

		$this->faker = \Faker\Factory::create('pt_BR');
	}
	/**
	 * @test
	 */
	public function shouldRegisterProfile()
	{
		$response = $this->post('/app/user/profile', [
			'first_name' => 'Olávo',
			'last_name' => 'Sales',
			'cpf' => '70369233000'
		], $this->auth);

		$response->assertJsonStructure([
			'success',
			'data' => [
				'first_name',
				'last_name',
				'cpf',
				'user_id',
				'uuid',
				'updated_at',
				'created_at',
				'id'
			]
		]);

		$response->assertSuccessful();
	}

	/**
	 * @test
	 */
	public function shoudNotRegisterProfile()
	{
		$response = $this->post('/app/user/profile', [], $this->auth);

		$response->assertJson(
			[
				'success' => false,
				'error' => [
					'first_name' => [
						0 => 'O campo primeiro nome é obrigatório.'
					],
					'last_name' => [
						0 => 'O campo sobrenome é obrigatório.'
					],
					'cpf' => [
						0 => 'O campo cpf é obrigatório.'
					]
				]
			]
		);

		$response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
	}

	/**
	 * @test
	 */
	public function shoudNotRegisterUserAndReturnAllOthersErrors()
	{
		$response = $this->post('/app/user/profile', [
				'first_name' => 'hakuna12', 'last_name' => '123', 'cpf' => 00000
			],
			$this->auth
		);

		$response->assertJson([
			'success' => false,
			'error' => [
				'first_name' => [
					'O campo primeiro nome só pode conter letras.'
				],
				'last_name' => [
					'O campo sobrenome só pode conter letras.'
				],
				'cpf' => [
					'CPF inválido'
				],
			]
		]);

		$response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
	}

	/**
	 * @test
	 */
	public function shoudNotRegisterProfilewithInvalidToken()
	{
		$response = $this->post('/app/user/profile');

		$response->assertJson([
			'success' => false,
			'error' => [
				'message' => 'Token de autorização não encontrado'
			]
		]);

		$response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
	}
}
