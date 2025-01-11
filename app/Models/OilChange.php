<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilChange extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'cost', 'date'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
