<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $role = ['user' , 'staff'];
        return [
            'username' => $this->faker->username,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('12345'),
            'role' => $role[array_rand($role)],
            'description' => 'lorem ipsum dolor sit amet'
        ];
    }
}
