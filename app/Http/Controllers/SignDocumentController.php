<?php

namespace App\Http\Controllers;

use App\Actions\SignDocument;
use App\Http\Resources\DocumentResource;

class SignDocumentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SignDocument $signDocument): DocumentResource
    {
        // Sign the document.
        $document = $signDocument->sign();

        // Return the signed document.
        return new DocumentResource($document);
    }
}
