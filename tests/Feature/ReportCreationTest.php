<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_anonymous_report_can_be_created(): void
    {
        $response = $this->postJson('/api/reports', [
            'title' => 'Test Report',
            'description' => 'This is a test report description',
            'is_anonymous' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'report_id',
                'credentials' => ['username', 'password', 'access_token', 'access_url'],
            ]);

        $this->assertDatabaseHas('reports', [
            'title' => 'Test Report',
            'is_anonymous' => true,
            'status' => 'eingegangen',
        ]);
    }

    public function test_registered_user_report_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/reports', [
            'title' => 'User Report',
            'description' => 'Report from registered user',
            'is_anonymous' => false,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reports', [
            'title' => 'User Report',
            'user_id' => $user->id,
            'is_anonymous' => false,
        ]);
    }

    public function test_report_requires_title(): void
    {
        $response = $this->postJson('/api/reports', [
            'description' => 'Description without title',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_report_requires_description(): void
    {
        $response = $this->postJson('/api/reports', [
            'title' => 'Title without description',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['description']);
    }

    public function test_incident_date_cannot_be_in_future(): void
    {
        $futureDate = now()->addDays(5)->format('Y-m-d');

        $response = $this->postJson('/api/reports', [
            'title' => 'Test Report',
            'description' => 'Test description',
            'incident_date' => $futureDate,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['incident_date']);
    }

    public function test_incident_date_can_be_today_or_past(): void
    {
        $response = $this->postJson('/api/reports', [
            'title' => 'Test Report',
            'description' => 'Test description',
            'incident_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(201);
    }
}
