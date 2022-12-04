<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('pt_BR');
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'name' => $faker->name,
            'cpf' => $faker->cpf(false),
            'password' => '$2y$04$5qd73lxRXokcj/7nQhwj9e8jOgHK5S.rez.ztAzUspVAVMZinc4hS',
            'remember_token' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
