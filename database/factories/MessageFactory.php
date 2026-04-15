<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'sender_type' => fake()->randomElement(['admin', 'whistleblower']),
            'sender_id' => null,
            'message' => fake()->paragraph(),
            'is_read' => false,
        ];
    }

    public function fromAdmin(User $admin): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
        ]);
    }

    public function fromWhistleblower(): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_type' => 'whistleblower',
            'sender_id' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }
}
