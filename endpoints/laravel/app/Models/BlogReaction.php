<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reaction_id',
        'blog_id',
        'poster_id',
        'type'
    ];    

    public function poster() : BelongsTo
    {
        return $this->belongsTo(User::class, 'poster_id');
    }

    public function blog() : BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}