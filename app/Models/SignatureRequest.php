<?php

namespace App\Models;

use Creagia\LaravelSignPad\Contracts\ShouldGenerateSignatureDocument;
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use Creagia\LaravelSignPad\SignaturePosition;
use Creagia\LaravelSignPad\Templates\PdfDocumentTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SignatureRequest extends Model implements ShouldGenerateSignatureDocument
{
    use HasFactory;

    // Constants for signature request statuses.
    const STATUS_PENDING = 'Pending';
    const STATUS_SIGNED = 'Signed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_id',
        'requested_by',
        'requested_from',
        'deadline',
        'signature_positions',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'signature_positions' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function getSignatureDocumentTemplate(): SignatureDocumentTemplate
    {
        $signaturePositions = [];

        foreach ($this->signature_positions as $position) {
            $signaturePosition[] = new SignaturePosition(
                signaturePage: $position['page'],
                signatureX: $position['X'],
                signatureY: $position['Y'],
            );
        }

        return new SignatureDocumentTemplate(
            outputPdfPrefix: 'signed',
            template: new PdfDocumentTemplate(Storage::path($this->document->filepath)), // Uncomment for PDF template
            signaturePositions: $signaturePositions
        );
    }

    // A request belongs to a document.
    public function document(): BelongsTo
    {
        return $this->belongsTo(related: Document::class);
    }

    // A request is requested by a user.
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'requested_by');
    }

    // A request is requested from a user.
    public function requestedFrom(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'requested_from');
    }
}
