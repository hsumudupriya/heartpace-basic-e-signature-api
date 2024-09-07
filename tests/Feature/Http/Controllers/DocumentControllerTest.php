<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_upload_a_document(): void
    {
        // Create a fake user for authentication.
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        // Fake the storage and create a fake PDF.
        Storage::fake();
        $document = UploadedFile::fake()->image('document.pdf');

        // Make a request to the api endpoint and assert the result.
        $this->postJson(
            uri: 'api/documents',
            data: [
                'document' => $document,
            ],
        )->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'filename',
                    'signature_status',
                    'signed_by',
                    'signed_at',
                ],
            ]);

        // Assert the storage has the file.
        Storage::assertExists(sprintf('users/%d/documents/%s', $user->id, $document->hashName()));

        // Assert the database has a record.
        $this->assertDatabaseHas('documents', [
            'user_id' => $user->id,
            'filename' => $document->getClientOriginalName(),
            'filepath' => sprintf('users/%d/documents/%s', $user->id, $document->hashName()),
            'signature_status' => Document::SIGNATURE_NOT_NECESSARY,
        ]);
    }
}
