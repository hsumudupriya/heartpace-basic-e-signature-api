<?php

namespace App\Actions;

use App\Http\Requests\StoreSignatureRequestRequest;
use App\Models\Document;
use App\Models\SignatureRequest;
use App\Models\User;

class StoreSignatureRequest
{
    public function __construct(private StoreSignatureRequestRequest $request) {}

    /**
     * Validate and store a document.
     *
     * @param  array<string, string>  $input
     */
    public function store(): SignatureRequest
    {
        // Validate the request or throw 422 error response.
        $validatedData = $this->request->validated();

        // Get the document.
        $document = Document::findOrFail($validatedData['document_id']);

        // Store the signature request in the database.
        $signatureRequest = new SignatureRequest();
        $signatureRequest->document()->associate($document);
        $signatureRequest->deadline = $validatedData['deadline'];
        $signatureRequest->signature_positions = $validatedData['signature_positions'];

        // Associate the signature request with the requested from user.
        $requestedFrom = User::where('email', $validatedData['requested_from'])->firstOrFail();
        $signatureRequest->requestedFrom()->associate($requestedFrom);

        // Save the signature request with the authenticated user.
        $user = $this->request->user();
        $user->madeSignatureRequests()->save($signatureRequest);

        // Update the status of the document.
        $document->signature_status = Document::SIGNATURE_STATUS_PENDING;
        $document->save();

        return $signatureRequest;
    }
}
