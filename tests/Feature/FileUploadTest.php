<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use App\Models\Attachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_admin_can_upload_file_to_report(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/attachments", [
            'files' => [$file],
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('attachments', [
            'report_id' => $report->id,
            'original_filename' => 'document.pdf',
        ]);
    }

    public function test_whistleblower_can_upload_file_to_own_report(): void
    {
        $report = Report::factory()->create(['is_anonymous' => true]);
        session(['whistleblower_report_id' => $report->id]);

        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->postJson("/api/reports/{$report->id}/attachments", [
            'files' => [$file],
        ]);

        $response->assertStatus(201);
    }

    public function test_cannot_upload_file_to_other_users_report(): void
    {
        $report = Report::factory()->create(['is_anonymous' => true]);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->postJson("/api/reports/{$report->id}/attachments", [
            'files' => [$file],
        ]);

        $response->assertStatus(403);
    }

    public function test_cannot_upload_executable_files(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create();

        $file = UploadedFile::fake()->create('malware.exe', 100);

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/attachments", [
            'files' => [$file],
        ]);

        $response->assertStatus(422);
    }

    public function test_file_size_limit_is_enforced(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create();

        $file = UploadedFile::fake()->create('large.pdf', 15000); // 15MB

        $response = $this->actingAs($admin)->postJson("/api/reports/{$report->id}/attachments", [
            'files' => [$file],
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_download_attachment(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $report = Report::factory()->create();
        
        Storage::disk('local')->put('attachments/test.pdf', 'fake content');
        
        $attachment = Attachment::factory()->create([
            'report_id' => $report->id,
            'filename' => 'test.pdf',
        ]);

        $response = $this->actingAs($admin)->get("/attachments/{$attachment->id}/download");

        $response->assertStatus(200);
    }

    public function test_whistleblower_can_download_own_attachment(): void
    {
        $report = Report::factory()->create(['is_anonymous' => true]);
        session(['whistleblower_report_id' => $report->id]);

        Storage::disk('local')->put('attachments/test.pdf', 'fake content');

        $attachment = Attachment::factory()->create([
            'report_id' => $report->id,
            'filename' => 'test.pdf',
        ]);

        $response = $this->get("/attachments/{$attachment->id}/download");

        $response->assertStatus(200);
    }

    public function test_cannot_download_other_users_attachment(): void
    {
        $report = Report::factory()->create(['is_anonymous' => true]);
        
        Storage::disk('local')->put('attachments/test.pdf', 'fake content');

        $attachment = Attachment::factory()->create([
            'report_id' => $report->id,
            'filename' => 'test.pdf',
        ]);

        $response = $this->get("/attachments/{$attachment->id}/download");

        $response->assertStatus(403);
    }
}
