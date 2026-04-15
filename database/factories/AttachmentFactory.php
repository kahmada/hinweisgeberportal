<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'filename' => Str::random(40) . '.pdf',
            'original_filename' => fake()->word() . '.pdf',
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(1000, 1000000),
        ];
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'filename' => Str::random(40) . '.jpg',
            'original_filename' => fake()->word() . '.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'filename' => Str::random(40) . '.docx',
            'original_filename' => fake()->word() . '.docx',
            'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
}
