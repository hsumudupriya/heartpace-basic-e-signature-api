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
        $signatureImage = $this->request->file('signature')->get();

        $signatureRequest = SignatureRequest::findOrFail($validatedData['request_id']);
        $document = $signatureRequest->document;

        if ($document instanceof CanBeSigned && $document->hasBeenSigned()) {
            throw new ModelHasAlreadyBeenSigned;
        }

        $uuid = Str::uuid()->toString();
        $filename = "{$uuid}.png";
        $signatureObj = $document->signature()->create([
            'uuid' => $uuid,
            'from_ips' => $this->request->ips(),
            'filename' => $filename,
            'certified' => config('sign-pad.certify_documents'),
        ]);

        Storage::disk(config('sign-pad.disk_name'))->put($signatureObj->getSignatureImagePath(), $signatureImage);

        if ($signatureRequest instanceof ShouldGenerateSignatureDocument) {
            ($this->generateSignatureDocumentAction)(
                $signatureObj,
                $signatureRequest->getSignatureDocumentTemplate(),
                $signatureImage
            );
        }

        // Update the existing document with the signed document.
        $document->filepath = $signatureObj->getSignatureImageAbsolutePath();
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
