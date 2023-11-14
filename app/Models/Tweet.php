<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tweet extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['created_by'];

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function reactions(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class, 'reactions',
            'tweet_id',
            'user_id'
        )->withTimestamps();
    }

    public function getCreatedByAttribute()
    {
        return $this->postedBy;
    }
}
