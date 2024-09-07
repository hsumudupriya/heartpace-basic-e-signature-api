<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignatureRequest extends Model
{
    use HasFactory;

    // Constants for signature request statuses.
    const PENDING = 'Pending';
    const SIGNED = 'Signed';

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
