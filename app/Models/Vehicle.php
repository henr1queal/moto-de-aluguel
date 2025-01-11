<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'year',
        'license_plate',
        'renavam',
        'actual_km',
        'revision_period',
        'oil_period',
        'protection_value',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function oilChanges()
    {
        return $this->hasMany(OilChange::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}
