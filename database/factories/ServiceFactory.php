<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_name' => fake()->name(),
            'description' => fake()->description(),
            'price' => '50000',
            'duration' => rand(10,100),
            'category_id' => rand(1,5),
        ];
    }
}
