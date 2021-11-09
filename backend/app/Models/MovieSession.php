<?php

namespace App\Models;

use Database\Factories\MovieSessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSession extends Model
{
    use HasFactory;

    public const FREE = 'free';
    public const TAKEN = 'taken';

    protected static function newFactory()
    {
        return MovieSessionFactory::new();
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
