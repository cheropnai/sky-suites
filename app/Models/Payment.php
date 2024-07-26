<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'booking_id', 'payment_method', 'amount', 'transaction_id',
        'currency', 'status', 'details', 'order_id',
    ];
    public $timestamps = true;
    // Define relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}