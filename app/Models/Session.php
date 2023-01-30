<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'biker_id',
        'parcel_id',
        'expected_pickup_time',
        'expected_dropoff_time',
        'pickup_time',
        'dropoff_time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'biker_id' => 'integer',
        'parcel_id' => 'integer',
        'expected_pickup_time' => 'timestamp',
        'expected_dropoff_time' => 'timestamp',
        'pickup_time' => 'timestamp',
        'dropoff_time' => 'timestamp',
    ];

    public function biker()
    {
        return $this->belongsTo(Biker::class);
    }

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }
}
