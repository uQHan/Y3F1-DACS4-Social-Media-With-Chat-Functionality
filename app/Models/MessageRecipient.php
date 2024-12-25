<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MessageRecipient extends Model
{
/**
     * Get the user that owns the MessageRecipient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'recipient_group_id');
    }

    protected $fillable = [
        'message_id',
        'recipient_id',
        'recipient_group_id',
        'is_read',
    ];
}
