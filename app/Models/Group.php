<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_memberships');
    }
    protected $fillable = [
        'name',
        'leader_id',
        'is_active',
    ];
}
