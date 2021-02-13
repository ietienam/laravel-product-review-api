<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Get the reviews of the product
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    /**
     * Get the user that owns the product
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
