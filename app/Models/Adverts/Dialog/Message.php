<?php

namespace App\Models\Adverts\Dialog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * $property int $id
 * $property Carbon $created_at
 * $property Carbon $updated_at
 * $property int $user_id
 * $property string $message
 */
class Message extends Model
{
    protected $table = 'advert_dialog_messages';

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
