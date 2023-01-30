<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;

class Customer extends User
{
    protected $table = 'users';

    public static function booted()
    {
        static::addGlobalScope('customer', function (Builder $builder) {
            $builder->where(['role' => Role::CUSTOMER]);
        });
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->role = Role::CUSTOMER;
        });
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }
}
