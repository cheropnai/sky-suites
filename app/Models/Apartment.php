<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{   protected $fillable = [
    'name','description','price','city_id','image','address',
];
public function bookings()
 {
    return $this->hasMany(Booking::class);
 }
 public function city()
 {
    return $this->belongsTo(City::class);
 }
 public function images()
 {
     return $this->hasMany(Image::class);
 }
 public function scopeUnbooked($query, $startDate, $endDate)
 {
     return $query->whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {
         $query->where(function ($q) use ($startDate, $endDate) {
             $q->whereBetween('start_date', [$startDate, $endDate])
                 ->orWhereBetween('end_date', [$startDate, $endDate])
                 ->orWhere(function ($q2) use ($startDate, $endDate) {
                     $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                 });
         });
     });
 }
}