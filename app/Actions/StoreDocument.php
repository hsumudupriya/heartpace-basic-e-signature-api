<?php

namespace App\Actions;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;

class StoreDocument
{
    /**
     * Validate and store a document.
     *
     * @param  array<string, string>  $input
     */
    public function store(StoreDocumentRequest $request): Document
    {
        // Validate the request or throw 422 error response.
        $request->validated();

        // Store the document in the user's directory.
        $user = $request->user();
        $file = $request->file('document');
        $userDirectory = sprintf('users/%d/documents', $user->id);
        $filepath = $file->store($userDirectory);

        // Store the document in the database.
        $document = new Document();
        $document->filename = $file->getClientOriginalName();
        $document->filepath = $filepath;
        $user->documents()->save($document);

        return $document;
    }
}
