<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VolunteerEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $startDate = fake()->dateTimeBetween('+1 week', '+1 month');

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . uniqid(),
            'description' => fake()->paragraph(5),
            'location' => fake()->city(),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, (clone $startDate)->modify('+5 days')),
            'status' => fake()->randomElement(['upcoming', 'registration_open']),
            'banner_image' => fake()->imageUrl(640, 480, 'nature', true),
        ];
    }
}