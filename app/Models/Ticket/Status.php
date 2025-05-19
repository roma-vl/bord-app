<?php

namespace App\Models\Ticket;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $status
 */
class Status extends Model
{
    public const string OPEN       = 'open';
    public const string APPROVED   = 'approved';
    public const string PENDING    = 'pending';
    public const string PROCESSING = 'processing';
    public const string CANCELLED  = 'cancelled';
    public const string CLOSED     = 'closed';

    protected $table = 'ticket_statuses';
    protected $guarded = ['id'];

    public static function statusesList(): array
    {
        return [
            self::OPEN       => __('Open'),
            self::APPROVED   => __('Approved'),
            self::PENDING    => __('Pending'),
            self::PROCESSING => __('Processing'),
            self::CANCELLED  => __('Cancelled'),
            self::CLOSED     => __('Closed')
        ];
    }

    public function isOpen(): bool
    {
        return $this->status = self::OPEN;
    }

    public function isApproved(): bool
    {
        return $this->status = self::APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status = self::PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status = self::PROCESSING;
    }

    public function isCancelled(): bool
    {
        return $this->status = self::CANCELLED;
    }

    public function isClosed(): bool
    {
        return $this->status = self::CLOSED;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
