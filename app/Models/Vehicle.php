<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'first_declared_km',
        'next_oil_change',
        'next_revision',
        'user_id',
        'observation'
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function oilChanges(): HasMany
    {
        return $this->hasMany(OilChange::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    public function mileageHistory(): HasMany
    {
        return $this->hasMany(MileageHistory::class);
    }

    public function actualRental(): HasOne
    {
        return $this->hasOne(Rental::class)->whereNull('finished_at')->latest();
    }

    public function latestMaintenance(): HasOne
    {
        return $this->hasOne(Maintenance::class)->orderByDesc('date')->latest();
    }

    public function latestOilChange(): HasOne
    {
        return $this->hasOne(OilChange::class)->orderByDesc('date')->latest();
    }

    public function setLicensePlateAttribute($value)
    {
        $this->attributes['license_plate'] = strtoupper($value);
    }
}
