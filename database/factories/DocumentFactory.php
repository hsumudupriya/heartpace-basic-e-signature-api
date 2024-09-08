<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filepath = fake()->filePath();
        $signatureStatuses = [
            Document::SIGNATURE_STATUS_NOT_NECESSARY,
            Document::SIGNATURE_STATUS_PENDING,
            Document::SIGNATURE_STATUS_SIGNED,
        ];

        return [
            'user_id' => User::factory()->create()->id,
            'filename' => basename($filepath),
            'filepath' => $filepath,
            'signature_status' => $signatureStatuses[rand(0, count($signatureStatuses) - 1)],
        ];
    }
}
