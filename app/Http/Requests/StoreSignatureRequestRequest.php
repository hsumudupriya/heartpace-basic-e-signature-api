<?php

namespace App\Http\Requests;

use App\Models\Document;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSignatureRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $document = null;

        return [
            'document_id' => [
                'bail',
                'required',
                'integer',

                // Valide if the document exists.
                function (string $attribute, mixed $documentId, Closure $fail) use (&$document) {
                    $document = Document::findOr($documentId, function () use ($fail) {
                        $fail('The document does not exist.');
                    });
                },

                // Validate if the document belongs to the requesting user.
                function (string $attribute, mixed $documentId, Closure $fail) use (&$document) {
                    if ($document->user_id !== Auth::user()->id) {
                        $fail('The document does not belong to you.');
                    }
                },

                // Validate if the document is not signed or not pending to be signed.
                function (string $attribute, mixed $documentId, Closure $fail) use (&$document) {
                    if (
                        $document->signature_status === Document::SIGNATURE_STATUS_SIGNED ||
                        $document->signature_status === Document::SIGNATURE_STATUS_PENDING
                    ) {
                        $fail('The document is already signed or has a pending request.');
                    }
                },
            ],
            'requested_from' => [
                'bail',
                'required',
                'email',

                // Validate if the user from whom the signature is requested exists.
                function (string $attribute, mixed $requestedFrom, Closure $fail) {
                    User::where('email', '=', $requestedFrom)->firstOr(function () use ($fail) {
                        $fail('The requested user has not been registered with us.');
                    });
                },
            ],
            'signature_positions' => ['required', 'array'],
            'signature_positions.*.page' => ['required', 'integer'],
            'signature_positions.*.X' => ['required', 'integer'],
            'signature_positions.*.Y' => ['required', 'integer'],
            'deadline' => ['required', 'date_format:Y-m-d\TH:i:sP', 'after_or_equal:now'],
        ];
    }
}
