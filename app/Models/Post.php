<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }
    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }
    public function participations(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participations')->withTimestamps();
    }
    protected $fillable = [
        'creator_id',
        'type',
        'title',
        'content',
        'image_url',
        'status',
        // 'location',
        // 'current_waypoint',
    ];
}
