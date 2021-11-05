<?php

namespace App\Models;

use Database\Factories\MovieSessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSession extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return MovieSessionFactory::new();
    }
}
