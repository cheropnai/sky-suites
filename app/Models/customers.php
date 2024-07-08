<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    protected $fillable=[
        'phone_number','user_id','city_id'
    ];
public function user(){
    return $this->belongsTo(User::class);
}
public function city(){
    return $this->belongsTo(City::class);
}
}
