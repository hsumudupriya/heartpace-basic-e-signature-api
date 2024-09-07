<?php

namespace App\Actions;

use App\Http\Requests\StoreSignatureRequestRequest;
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
        $validated = $this->request->validated();

        // Store the signature request in the database.
        $signatureRequest = new SignatureRequest();
        $signatureRequest->document_id = $validated['document_id'];
        $signatureRequest->deadline = $validated['deadline'];
        $signatureRequest->signature_positions = $validated['signature_positions'];

        // Associate the signature request with the requested from user.
        $requestedFrom = User::where('email', $validated['requested_from'])->firstOrFail();
        $signatureRequest->requestedFrom()->associate($requestedFrom);

        // Save the signature request with the authenticated user.
        $user = $this->request->user();
        $user->madeSignatureRequests()->save($signatureRequest);

        return $signatureRequest;
    }
}
