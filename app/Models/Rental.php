<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Rental extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'landlord_name',
        'landlord_cpf',
        'driver_license_number',
        'driver_license_issue_date',
        'birth_date',
        'phone_1',
        'phone_2',
        'mother_name',
        'father_name',
        'start_date',
        'end_date',
        'cost',
        'deposit',
        'zip_code',
        'state',
        'city',
        'neighborhood',
        'street',
        'house_number',
        'complement',
        'photo',
        'vehicle_id',
        'finished_at'
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

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
