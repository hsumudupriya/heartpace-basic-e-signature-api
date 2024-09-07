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
                function (string $attribute, mixed $documentId, Closure $fail) use (&$document) {
                    $document = Document::findOr($documentId, function () use ($fail) {
                        $fail('The document does not exist.');
                    });
                },
                function (string $attribute, mixed $documentId, Closure $fail) use (&$document) {
                    if ($document->user_id !== Auth::user()->id) {
                        $fail('The document does not belong to you.');
                    }
                },
            ],
            'requested_from' => [
                'bail',
                'required',
                'email',
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
