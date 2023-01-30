<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcel extends Model
{
    use HasFactory, SoftDeletes;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($parcel) {
            $parcel->status = ParcelStatus::PENDING;
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'pickup_address',
        'delivery_address',
        'status',
        'customer_id',
        'biker_id',
        'description',
        'expected_pickup_time',
        'expected_dropoff_time',
        'pickup_time',
        'dropoff_time'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expected_pickup_time' => 'datetime',
        'expected_dropoff_time' => 'datetime',
        'pickup_time' => 'datetime',
        'dropoff_time' => 'datetime'
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function biker()
    {
        return $this->belongsTo(Biker::class);
    }

    public function bikers()
    {
        return $this->hasManyThrough(Biker::class, Session::class, 'parcel_id', 'id', 'id', 'biker_id');
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ParcelStatus::wording($value)
        );
    }

    public function scopeAvailable()
    {
        return $this->where(['status' => ParcelStatus::PENDING]);
    }
}
