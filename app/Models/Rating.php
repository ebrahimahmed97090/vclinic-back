<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';
    protected $fillable = ['ratingable_id', 'ratingable_type', 'rating', 'name', 'email'];

    public function ratingable()
    {
        return $this->morphTo();
    }
}
