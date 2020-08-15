<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'language', 'year_published', 'isbn','user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
