<?php

namespace App\Actions;

use App\Http\Requests\SignDocumentRequest;
use App\Models\Document;
use App\Models\SignatureRequest;
use Creagia\LaravelSignPad\Actions\GenerateSignatureDocumentAction;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Creagia\LaravelSignPad\Contracts\ShouldGenerateSignatureDocument;
use Creagia\LaravelSignPad\Exceptions\ModelHasAlreadyBeenSigned;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SignDocument
{
    public function __construct(
        private SignDocumentRequest $request,
        private GenerateSignatureDocumentAction $generateSignatureDocumentAction
    ) {}

    /**
     * Sign a document.
     *
     * @param  array<string, string>  $input
     */
    public function sign(): Document
    {
        // Validate the request or throw 422 error response.
        $validatedData = $this->request->validated();
        $signatureFile = $this->request->file('signature');

        // Fetch the signature request and the document.
        $signatureRequest = SignatureRequest::findOrFail($validatedData['request_id']);
        $document = $signatureRequest->document;

        // Throw an exception is the document has been signed.
        if ($document instanceof CanBeSigned && $document->hasBeenSigned()) {
            throw new ModelHasAlreadyBeenSigned;
        }

        // Create the signature object.
        $uuid = Str::uuid()->toString();
        $filename = "{$uuid}.png";
        $signatureObj = $document->signature()->create([
            'uuid' => $uuid,
            'from_ips' => $this->request->ips(),
            'filename' => $filename,
            'certified' => config('sign-pad.certify_documents'),
        ]);

        // Store the signature as a file.
        Storage::disk(config('sign-pad.disk_name'))->put($signatureObj->getSignatureImagePath(), $signatureFile->get());

        if ($signatureRequest instanceof ShouldGenerateSignatureDocument) {
            // Set the dimensions of the sign pad to the dimensions of the image.
            // $signatureSizes = getimagesize($signatureFile->path());
            // config([
            //     'sign-pad.width' => $signatureSizes[0],
            //     'sign-pad.height' => $signatureSizes[1],
            // ]);

            // Generate the signed document.
            ($this->generateSignatureDocumentAction)(
                $signatureObj,
                $signatureRequest->getSignatureDocumentTemplate(),
                $signatureFile->get()
            );
        }

        // Update the existing document with the signed document.
        $document->filepath = $signatureObj->getSignedDocumentPath();
        $document->signature_status = Document::SIGNATURE_STATUS_SIGNED;
        $document->signedBy()->associate($this->request->user());
        $document->signed_at = now();
        $document->save();

        // Update the signature request.
        $signatureRequest->status = SignatureRequest::STATUS_SIGNED;
        $signatureRequest->save();

        return $document;
    }
}
