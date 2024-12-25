<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_memberships');
    }

    /**
     * Get all of the comments for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(MessageRecipient::class, 'recipient_group_id');
    }
    protected $fillable = [
        'name',
        'leader_id',
        'is_active',
    ];
}
