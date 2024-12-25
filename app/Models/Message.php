<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function recipient(): HasMany
    {
        return $this->hasMany(MessageRecipient::class);
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }
    public function child(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }
    protected $fillable = [
        'creator_id',
        'content',
        'image_url',
        'parent_message_id',
        'is_deleted',
    ];
}
