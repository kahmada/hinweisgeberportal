<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_message_to_report(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create();

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/messages", [
            'message' => 'Admin message to whistleblower',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('messages', [
            'report_id' => $report->id,
            'sender_type' => 'admin',
            'message' => 'Admin message to whistleblower',
        ]);
    }

    public function test_whistleblower_can_send_message(): void
    {
        $report = Report::factory()->create(['is_anonymous' => true]);

        session(['whistleblower_report_id' => $report->id]);

        $response = $this->postJson("/api/reports/{$report->id}/messages", [
            'message' => 'Whistleblower response',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('messages', [
            'report_id' => $report->id,
            'sender_type' => 'whistleblower',
            'message' => 'Whistleblower response',
        ]);
    }

    public function test_cannot_send_message_to_closed_report(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create(['status' => 'abgeschlossen']);

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/messages", [
            'message' => 'Message to closed report',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_unauthorized_user_cannot_send_message(): void
    {
        $report = Report::factory()->create();

        $response = $this->postJson("/api/reports/{$report->id}/messages", [
            'message' => 'Unauthorized message',
        ]);

        $response->assertStatus(403);
    }

    public function test_messages_can_be_retrieved(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create();
        
        Message::factory()->count(3)->create([
            'report_id' => $report->id,
        ]);

        $response = $this->actingAs($admin)->getJson("/api/reports/{$report->id}/messages");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'messages' => [
                    '*' => ['id', 'report_id', 'sender_type', 'message', 'created_at'],
                ],
            ]);
    }

    public function test_messages_can_be_marked_as_read(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create();
        
        Message::factory()->create([
            'report_id' => $report->id,
            'sender_type' => 'whistleblower',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/messages/mark-read");

        $response->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'report_id' => $report->id,
            'sender_type' => 'whistleblower',
            'is_read' => true,
        ]);
    }
}
