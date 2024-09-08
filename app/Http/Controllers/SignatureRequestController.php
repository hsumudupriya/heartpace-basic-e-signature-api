<?php

namespace App\Http\Controllers;

use App\Actions\StoreSignatureRequest;
use App\Http\Requests\StoreSignatureRequestRequest;
use App\Http\Resources\SignatureRequestResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class SignatureRequestController extends Controller
{
    /**
     * Get made requests
     *
     * Returns the list of signature requests made by the authorized user.
     *
     * @group Signature requests
     * @apiResourceCollection App\Http\Resources\SignatureRequestResource
     */
    public function index(): ResourceCollection
    {
        // Get the authorized user from the request.
        $user = Auth::user();

        // Return the user's signature requests as a collection.
        return SignatureRequestResource::collection($user->madeSignatureRequests);
    }

    /**
     * Create a request
     *
     * @group Signature requests
     * @apiResource App\Http\Resources\SignatureRequestResource
     */
    public function store(StoreSignatureRequestRequest $request, StoreSignatureRequest $storeSignatureRequest): SignatureRequestResource
    {
        // Store the signature request.
        $signatureRequest = $storeSignatureRequest->store($request);

        // Return the new signature request.
        return new SignatureRequestResource($signatureRequest);
    }

    /**
     * Get received requests
     *
     * Return the list of signature requests received by the authorized user.
     *
     * @group Signature requests
     * @apiResourceCollection App\Http\Resources\SignatureRequestResource
     */
    public function receivedSignatureRequests(): ResourceCollection
    {
        // Get the authorized user from the request.
        $user = Auth::user();

        // Return the user's signature requests as a collection.
        return SignatureRequestResource::collection($user->receivedSignatureRequests);
    }
}
