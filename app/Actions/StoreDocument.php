<?php

namespace App\Actions;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;

class StoreDocument
{
    public function __construct(private StoreDocumentRequest $request) {}

    /**
     * Validate and store a document.
     *
     * @param  array<string, string>  $input
     */
    public function store(): Document
    {
        // Validate the request or throw 422 error response.
        $this->request->validated();

        // Store the document in the user's directory.
        $user = $this->request->user();
        $file = $this->request->file('document');
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
