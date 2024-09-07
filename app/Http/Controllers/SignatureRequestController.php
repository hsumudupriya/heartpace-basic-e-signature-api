<?php

namespace App\Http\Controllers;

use App\Actions\StoreSignatureRequest;
use App\Http\Resources\SignatureRequestResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class SignatureRequestController extends Controller
{
    /**
     * Return the list of signature requests made by the authorized user.
     */
    public function index(): ResourceCollection
    {
        // Get the authorized user from the request.
        $user = Auth::user();

        // Return the user's signature requests as a collection.
        return SignatureRequestResource::collection($user->madeSignatureRequests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSignatureRequest $storeSignatureRequest): SignatureRequestResource
    {
        // Store the signature request.
        $signatureRequest = $storeSignatureRequest->store();

        // Return the new signature request.
        return new SignatureRequestResource($signatureRequest);
    }

    /**
     * Return the list of signature requests received by the authorized user.
     */
    public function receivedSignatureRequests(): ResourceCollection
    {
        // Get the authorized user from the request.
        $user = Auth::user();

        // Return the user's signature requests as a collection.
        return SignatureRequestResource::collection($user->receivedSignatureRequests);
    }
}
