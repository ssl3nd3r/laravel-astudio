<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'department' => $this->faker->streetAddress(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(), // password
            'status' => $this->faker->numberBetween(0,4),
        ];
    }
}
