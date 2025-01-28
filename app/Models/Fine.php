<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Fine extends Model
{
    use HasFactory, SoftDeletes;

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
    
    protected $fillable = [
        'date',
        'cost',
        'paid',
        'observation',
        'vehicle_id',
        'rental_id'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
