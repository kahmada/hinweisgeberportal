<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'title' => fake()->sentence(),
            'company' => fake()->company(),
            'violation_type' => fake()->randomElement(['Korruption', 'Betrug', 'Diskriminierung', 'Sicherheitsverstoß']),
            'incident_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'incident_location' => fake()->city(),
            'involved_persons' => fake()->name(),
            'description' => fake()->paragraph(),
            'status' => 'eingegangen',
            'is_anonymous' => false,
            'anonymous_username' => null,
            'anonymous_password' => null,
            'anonymous_token' => null,
            'identity_revealed_at' => null,
            'identity_revealed_by' => null,
        ];
    }

    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_anonymous' => true,
            'user_id' => null,
            'anonymous_username' => 'WB-' . strtoupper(Str::random(8)),
            'anonymous_password' => bcrypt('password'),
            'anonymous_token' => Str::random(64),
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'is_anonymous' => false,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'abgeschlossen',
        ]);
    }
}
