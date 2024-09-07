<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignatureRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'document' => new DocumentResource($this->document),
            'requested_by' => new UserResource($this->requestedBy),
            'requested_from' => new UserResource($this->requestedFrom),
            'deadline' => $this->deadline,
            'signature_positions' => $this->signature_positions,
            'requested_at' => $this->created_at,
            'status' => $this->status,
        ];
    }
}
