<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    protected $fillable = [
        'creator_id',
        'post_id',
        'content',
        'image_url',
        'parent_comment_id',
        'status',
    ];
}
