<?php

namespace App\Http\Controllers;

use App\Actions\StoreDocument;
use App\Http\Resources\DocumentResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        // Get the authorized user from the request.
        $user = Auth::user();

        // Return the user's documents as a collection.
        return DocumentResource::collection($user->documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocument $storeDocument): DocumentResource
    {
        // Store the document.
        $document = $storeDocument->store();

        // Return the new document.
        return new DocumentResource($document);
    }
}
