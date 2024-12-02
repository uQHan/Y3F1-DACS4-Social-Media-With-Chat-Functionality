<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relationship extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function other(): BelongsTo
    {
        return $this->belongsTo(User::class, 'other_id');
    }

    protected $fillable =[
        'user_id',
        'other_id',
        'type',
        // 'is_friend'
    ];
}
