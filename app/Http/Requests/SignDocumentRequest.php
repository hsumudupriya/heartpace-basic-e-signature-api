<?php

namespace App\Http\Requests;

use App\Models\SignatureRequest;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class SignDocumentRequest extends FormRequest
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
        $request = null;

        return [
            'request_id' => [
                'bail',
                'required',
                'integer',

                // Validate if the request exists.
                function (string $attribute, mixed $requestId, Closure $fail) use (&$request) {
                    $request = SignatureRequest::findOr($requestId, function () use ($fail) {
                        $fail('The request does not exist.');
                    });
                },

                // Validate if the request is for the user.
                function (string $attribute, mixed $requestId, Closure $fail) use (&$request) {
                    if ($request->requested_from !== Auth::user()->id) {
                        $fail('The request is not for you.');
                    }
                },

                // Validate if the document is already signed.
                function (string $attribute, mixed $requestId, Closure $fail) use (&$request) {
                    if ($request->status === SignatureRequest::STATUS_SIGNED) {
                        $fail('The document is already signed.');
                    }
                },

                // Validate if the deadline is passed.
                function (string $attribute, mixed $requestId, Closure $fail) use (&$request) {
                    if ($request->deadline->isPast()) {
                        $fail('The deadline to sign the document has been passed.');
                    }
                },

            ],
            'signature' => [
                'required',
                'file',
                // only png, jpg, or jpeg files less than 10 MB in size are accepted
                File::types(['png', 'jpg', 'jpeg'])->max('10mb'),
            ]
        ];
    }
}
