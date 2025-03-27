<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
