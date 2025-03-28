<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relations
    public function maintenances()
    {
        return $this->belongsToMany(Maintenance::class, 'maintenance_part')
                    ->withPivot('observation');
    }
}

