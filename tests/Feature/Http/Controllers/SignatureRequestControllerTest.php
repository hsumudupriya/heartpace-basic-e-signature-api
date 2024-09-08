<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SignatureRequestControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_send_a_signature_request(): void
    {
        // Create fake users.
        $requestedByUser = User::factory()->create();
        $requestedFromUser = User::factory()->create();

        // Authorize the requested by user to send a signature request.
        $this->actingAs($requestedByUser, 'web');
        // Create a document for requesting the signature.
        $document = Document::factory()->create([
            'user_id' => $requestedByUser->id,
            'signature_status' => Document::SIGNATURE_STATUS_NOT_NECESSARY,
        ]);
        $deadline = now()->addDay();
        $signaturePositions = [
            [
                'X' => 50,
                'Y' => 50,
                'page' => 1,
            ]
        ];

        // Make a request to the api endpoint and assert the result.
        $this->postJson('api/signature-requests', [
            'document_id' => $document->id,
            'requested_from' => $requestedFromUser->email,
            'deadline' => $deadline->format('Y-m-d\TH:i:sP'),
            'signature_positions' => $signaturePositions,
        ])->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'document',
                    'requested_by',
                    'requested_from',
                    'deadline',
                    'signature_positions',
                    'requested_at',
                    'status',
                ],
            ]);

        // Assert the database has a record.
        $this->assertDatabaseHas('signature_requests', [
            'document_id' => $document->id,
            'requested_by' => $requestedByUser->id,
            'requested_from' => $requestedFromUser->id,
            'deadline' => $deadline->format('Y-m-d H:i:s'),
        ]);
    }

    public function test_a_user_cannot_send_a_signature_request_for_pending_document(): void
    {
        // Create fake users.
        $requestedByUser = User::factory()->create();
        $requestedFromUser = User::factory()->create();

        // Authorize the requested by user to send a signature request.
        $this->actingAs($requestedByUser, 'web');
        // Create a document for requesting the signature with pending status.
        $document = Document::factory()->create([
            'user_id' => $requestedByUser->id,
            'signature_status' => Document::SIGNATURE_STATUS_PENDING,
        ]);
        $deadline = now()->addDay();
        $signaturePositions = [
            [
                'X' => 50,
                'Y' => 50,
                'page' => 1,
            ]
        ];

        // Make a request to the api endpoint and assert that the request fails.
        $this->postJson('api/signature-requests', [
            'document_id' => $document->id,
            'requested_from' => $requestedFromUser->email,
            'deadline' => $deadline->format('Y-m-d\TH:i:sP'),
            'signature_positions' => $signaturePositions,
        ])->assertUnprocessable()
            ->assertExactJsonStructure([
                'message',
                'errors' => [
                    'document_id',
                ],
            ]);

        // Assert the database does not have a record.
        $this->assertDatabaseEmpty('signature_requests');
    }

    public function test_a_user_cannot_send_a_signature_request_for_signed_document(): void
    {
        // Create fake users.
        $requestedByUser = User::factory()->create();
        $requestedFromUser = User::factory()->create();

        // Authorize the requested by user to send a signature request.
        $this->actingAs($requestedByUser, 'web');
        // Create a document for requesting the signature with pending status.
        $document = Document::factory()->create([
            'user_id' => $requestedByUser->id,
            'signature_status' => Document::SIGNATURE_STATUS_SIGNED,
        ]);
        $deadline = now()->addDay();
        $signaturePositions = [
            [
                'X' => 50,
                'Y' => 50,
                'page' => 1,
            ]
        ];

        // Make a request to the api endpoint and assert that the request fails.
        $this->postJson('api/signature-requests', [
            'document_id' => $document->id,
            'requested_from' => $requestedFromUser->email,
            'deadline' => $deadline->format('Y-m-d\TH:i:sP'),
            'signature_positions' => $signaturePositions,
        ])->assertUnprocessable()
            ->assertExactJsonStructure([
                'message',
                'errors' => [
                    'document_id',
                ],
            ]);

        // Assert the database does not have a record.
        $this->assertDatabaseEmpty('signature_requests');
    }
}
