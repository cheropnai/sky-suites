<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name'];

    // Define relationship to countries
    public function countries()
    {
        return $this->hasMany(Country::class);
    }
}
