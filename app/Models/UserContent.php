<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#TODO - check priv_stat and creator on fillable
class UserContent extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $table = 'user_content';

    public $timestamps = false;

    protected $fillable = [
      'text', 'creator_id', 'group_id', 'pinned', 'priv_stat'
    ];

    /**
     * This content's group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * This content's creator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function tagged(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tag', 'content_id', 'user_id')
                    ->using(Tag::class);
    }

    public function hasTags() {
        return !$this->tagged->isEmpty();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'like', 'content_id', 'user_id')
                    ->using(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function inGroup(): bool
    {
        return $this->group_id !== null;
    }

    public function isPost(): bool
    {
        return Post::find($this->id) !== null;
    }

    public function isComment(): bool
    {
        return Comment::find($this->id) !== null;
    }

    public function isShare(): bool
    {
        return Share::find($this->id) !== null;
    }

    public function hasComments(): bool
    {
        return !$this->comments->isEmpty();
    }

    public function sortedComments(): \Illuminate\Support\Collection
    {
        return collect($this->comments)->sort(function ($a, $b) {
            if ($a->content->timestamp === $b->content->timestamp) return 0;
            return $a->content->timestamp < $b->content->timestamp ? 1 : -1;
        });
    }

    public function delete()
    {
        $this->priv_stat = 'Anonymous';
        $this->save();
    }

    public function likeCount(): int
    {
        return $this->likes()->count();
    }

    public function likedByUser($id): bool
    {
        foreach ($this->likes as $user) {
            if ($user->id === $id) return true;
        }
        return false;
    }
}
