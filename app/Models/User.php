<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Model Relationship
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'creator_id')->orderBy('created_at', 'desc');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'creator_id');
    }
    public function created_messages(): HasMany
    {
        return $this->hasMany(Message::class, 'creator_id');
    }
    public function messages()
    {
        return $this->hasMany(MessageRecipient::class, 'creator_id')->orWhere('recipient_id', $this->id);
    }
    public function groups_created(): HasMany
    {
        return $this->hasMany(Group::class, 'leader_id');
    }
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_memberships');
    }
    public function relationships(): HasMany
    {
        return $this->hasMany(Relationship::class, 'user_id');
    }
    public function incoming_message(): BelongsTo
    {
        return $this->belongsTo(MessageRecipient::class);
    }
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }
    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'bookmarks')->withTimestamps();
    }
    public function participations(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'participations')->withTimestamps();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'pfp_url',
        'gender',
        'bio',
        'addess',
        'website',
        'last_online',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
