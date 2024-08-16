<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    

    // Define relationship to countries
    public function countries()
    {
        return $this->belongsTo(Country::class);
    }
}
