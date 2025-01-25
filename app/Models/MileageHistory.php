<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MileageHistory extends Model
{
    use HasFactory;

    protected $fillable = ['actual_km', 'vehicle_id', 'rental_id', 'observation'];

    /**
     * Get the vehicle that owns the MileageHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}
