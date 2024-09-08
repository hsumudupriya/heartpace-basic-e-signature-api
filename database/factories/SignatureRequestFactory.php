<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\SignatureRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SignatureRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stuses = [
            SignatureRequest::STATUS_PENDING,
            SignatureRequest::STATUS_SIGNED,
        ];

        return [
            'document_id' => Document::factory()->create()->id,
            'requested_by' => User::factory()->create()->id,
            'requested_from' => User::factory()->create()->id,
            'deadline' => now()->addDay(),
            'signature_positions' => [
                [
                    'X' => 50,
                    'Y' => 50,
                    'page' => 1,
                ]
            ],
            'status' => $stuses[rand(0, count($stuses) - 1)],
        ];
    }
}
