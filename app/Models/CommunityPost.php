<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'author_name', 'author_role', 'avatar_url', 'cover_url',
        'image_data', 'image_mime',
        'category', 'title', 'excerpt', 'content',
        'is_published', 'published_at',
        'likes_count', 'comments_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CommunityComment::class, 'post_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isOwnedBy(?User $user): bool
    {
        return $user && $this->user_id === $user->id;
    }

    public function getImageDataUriAttribute(): ?string
    {
        if (! $this->image_data || ! $this->image_mime) {
            return null;
        }
        return 'data:'.$this->image_mime.';base64,'.$this->image_data;
    }
}
