<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_reports(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Report::factory()->count(5)->create();

        $response = $this->actingAs($admin)->getJson('/api/reports');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_non_admin_cannot_view_all_reports(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        Report::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/reports');

        $response->assertStatus(403);
    }

    public function test_admin_can_update_report_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create(['status' => 'eingegangen']);

        $response = $this->actingAs($admin)->patchJson("/api/reports/{$report->id}", [
            'status' => 'in_pruefung',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'in_pruefung',
        ]);
    }

    public function test_non_admin_cannot_update_report_status(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $report = Report::factory()->create(['status' => 'eingegangen']);

        $response = $this->actingAs($user)->patchJson("/api/reports/{$report->id}", [
            'status' => 'in_pruefung',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_reveal_identity(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $report = Report::factory()->create([
            'user_id' => $user->id,
            'is_anonymous' => false,
        ]);

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/reveal-identity");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'identity_revealed_by' => $admin->id,
        ]);

        $this->assertNotNull(Report::find($report->id)->identity_revealed_at);
    }

    public function test_cannot_reveal_anonymous_report_identity(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create(['is_anonymous' => true]);

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/reveal-identity");

        $response->assertStatus(400);
    }

    public function test_non_admin_cannot_reveal_identity(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $targetUser = User::factory()->create();
        $report = Report::factory()->create([
            'user_id' => $targetUser->id,
            'is_anonymous' => false,
        ]);

        $response = $this->actingAs($user)->postJson("/api/reports/{$report->id}/reveal-identity");

        $response->assertStatus(403);
    }
}
