<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Store::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'address' => fake()->address(),
            'password' => bcrypt("AZE123aze"),
        ];
    }
}
