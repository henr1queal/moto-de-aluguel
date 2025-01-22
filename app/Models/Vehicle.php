<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $keyType = 'string'; // Indica que a chave primária é uma string.
    public $incrementing = false; // Desativa o autoincremento.

    protected static function boot()
    {
        parent::boot();

        // Gera UUID automaticamente ao criar um novo registro
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    public function scopeMyVehicles() {
        return $this->where('user_id', Auth()->user()->id);
    }

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
