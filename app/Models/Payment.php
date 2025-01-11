<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'paid',
        'cost',
        'payment_date',
        'observation',
        'rental_id',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
