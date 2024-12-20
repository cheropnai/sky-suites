<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable=[
        'departments','user_id',
    ];
public function user(){
    return $this->belongsTo(User::class);
}
public function city(){
    return $this->belongsTo(City::class);
}
}