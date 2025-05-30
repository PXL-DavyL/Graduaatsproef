<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'poster_id',
        'content'
    ];    

    public function poster() : BelongsTo
    {
        return $this->belongsTo(User::class, 'poster_id');
    }

    public function comments() : HasMany
    {
        return $this->hasMany(BlogComment::class);
    }
    
    public function reactions() : HasMany
    {
        return $this->hasMany(BlogReaction::class);
    }
}