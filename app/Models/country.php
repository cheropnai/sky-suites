<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    

    // Define relationship to countries
    public function regions()
    {
        return $this->belongsTo(Region::class);
    }

    public function countries()
    {
        return $this->hasMany(Country::class);
    }
}