<?php

namespace App\Models;

use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model implements CanBeSigned
{
    use HasFactory, RequiresSignature;

    // Constants for signature statuses.
    const SIGNATURE_STATUS_NOT_NECESSARY = 'Not necessary';
    const SIGNATURE_STATUS_PENDING = 'Pending';
    const SIGNATURE_STATUS_SIGNED = 'Signed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'filename',
        'filepath',
        'signature_status',
        'signed_by',
        'signed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    // A document belongs to a user.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // A document is signed by a user.
    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'signed_by');
    }
}
