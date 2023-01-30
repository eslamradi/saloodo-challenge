<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;

class Biker extends User
{
    protected $table = 'users';

    public static function booted()
    {
        static::addGlobalScope('biker', function (Builder $builder) {
            $builder->where(['role' => Role::BIKER]);
        });
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->role = Role::BIKER;
        });
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }

    public function isBusy()
    {
        return ($this->parcels()->whereIn('status', [ParcelStatus::BEING_PICKED_UP, ParcelStatus::BEING_DELIVERED])->count() > 0);
    }
}
