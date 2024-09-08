<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Document;
use App\Models\SignatureRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Http\Controllers\Traits\WithTestPdfContent;
use Tests\Feature\Http\Controllers\Traits\WithTestSignatureContent;
use Tests\TestCase;

class SignDocumentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithTestPdfContent, WithTestSignatureContent;

    public function test_a_user_can_sign_a_document(): void
    {
        // Create a fake user and authenticate for this request.
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        // Fake the storage and create a fake PDF.
        Storage::fake();
        $document = UploadedFile::fake()->createWithContent(
            name: 'document.pdf',
            content: base64_decode($this->testPdfContent),
        );
        $filepath = $document->store();

        // Create fake objects.
        $document = Document::factory()->create([
            'filename' => $document->getClientOriginalName(),
            'filepath' => $filepath,
            'signature_status' => Document::SIGNATURE_STATUS_PENDING,
        ]);
        $signatureRequest = SignatureRequest::factory()->create([
            'document_id' => $document->id,
            'requested_by' => $document->user_id,
            'requested_from' => $user->id,
            'status' => SignatureRequest::STATUS_PENDING,
        ]);
        // base64 encoded PNG image.
        $signatureFile = UploadedFile::fake()->createWithContent('signature.png', base64_decode($this->testSignatureContent));

        // Make a request to the api endpoint and assert the result.
        $this->postJson('api/sign-document', [
            'request_id' => $signatureRequest->id,
            'signature' => $signatureFile,
        ])->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'filename',
                    'signature_status',
                    'signed_by',
                    'signed_at',
                ],
            ]);

        // Assert the database records has been updated.
        $this->assertDatabaseHas('signature_requests', [
            'id' => $signatureRequest->id,
            'status' => SignatureRequest::STATUS_SIGNED,
        ]);
        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'signature_status' => Document::SIGNATURE_STATUS_SIGNED,
            'signed_by' => $user->id,
        ]);
        $this->assertDatabaseHas('signatures', [
            'model_type' => Document::class,
            'model_id' => $document->id,
        ]);

        // Assert the signature has been store in the storage.
        $signaturefilePath = $document->signature->getSignatureImagePath();
        Storage::disk(config('sign-pad.disk_name'))->assertExists($signaturefilePath);

        $storedContent = Storage::disk(config('sign-pad.disk_name'))->get($signaturefilePath);
        $this->assertSame($storedContent, base64_decode($this->testSignatureContent));
    }
}
