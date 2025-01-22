<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OilChange extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cost',
        'actual_km',
        'date',
        'observation',
        'vehicle_id',
        'rental_id',
        'maintenance_id'
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
