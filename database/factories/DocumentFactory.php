<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'category' => fake()->randomElement(['teachers', 'administrators', 'parents']),
            'recipient_name' => fake()->name(),
            'recipient_phone' => fake()->optional()->numerify('05########'),
            'recipient_email' => fake()->optional()->safeEmail(),
            'original_file' => 'school-documents/example.pdf',
            'status' => 'pending',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'pending',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'completed',
            'signed_at' => now(),
            'completed_at' => now(),
        ]);
    }

    public function signed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'signed',
            'signed_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'cancelled',
        ]);
    }

    public function forTeachers(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => 'teachers',
        ]);
    }

    public function forParents(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => 'parents',
        ]);
    }
}
