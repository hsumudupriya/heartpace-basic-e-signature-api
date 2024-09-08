<?php

namespace App\Http\Controllers;

use App\Actions\StoreDocument;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Get documents
     *
     * @group Documents
     * @apiResourceCollection App\Http\Resources\DocumentResource
     */
    public function index(): ResourceCollection
    {
        // Get the authorized user from the request.
        $user = Auth::user();

        // Return the user's documents as a collection.
        return DocumentResource::collection($user->documents);
    }

    /**
     * Upload a document
     *
     * @group Documents
     * @apiResource App\Http\Resources\DocumentResource
     */
    public function store(StoreDocumentRequest $request, StoreDocument $storeDocument): DocumentResource
    {
        // Store the document.
        $document = $storeDocument->store($request);

        // Return the new document.
        return new DocumentResource($document);
    }

    /**
     * Download a document
     *
     * Download a document uploaded or signed by the user.
     *
     * @group Documents
     */
    public function show(Document $document): ?StreamedResponse
    {
        // Return a file downloand if the document belongs to or is signed the authorized user and the document exists.
        $user = Auth::user();

        return ($document->user_id === $user->id || $document->signed_by === $user->id) &&
            Storage::exists($document->filepath) ?
            Storage::download($document->filepath, $document->filename)
            :
            null;
    }
}
