<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{   protected $fillable = [
    'name','description','price','image','city_id','address',
];
public function bookings()
 {
    return $this->hasMany(Booking::class);
 }
 public function city()
 {
    return $this->belongsTo(City::class);
 }

    
}
