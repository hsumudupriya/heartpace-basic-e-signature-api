<?php

namespace App\Http\Controllers;

use App\Actions\SignDocument;
use App\Http\Requests\SignDocumentRequest;
use App\Http\Resources\DocumentResource;

class SignDocumentController extends Controller
{
    /**
     * Sign a document
     *
     * @group Sign a document
     * @apiResource App\Http\Resources\DocumentResource
     */
    public function __invoke(SignDocumentRequest $request, SignDocument $signDocument): DocumentResource
    {
        // Sign the document.
        $document = $signDocument->sign($request);

        // Return the signed document.
        return new DocumentResource($document);
    }
}
