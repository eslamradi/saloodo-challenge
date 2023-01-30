<?php

namespace App\Repositories;

use App\Events\Parcel\ParcelCreated;
use App\Models\ParcelStatus;
use App\Models\User;
use Carbon\Carbon;

class ParcelRepository extends Repository
{
    /**
     * get list of parcels associated with a user
     *
     * @param User $user
     * @return Paginator
     */
    public function get(User $user)
    {
        return $user->parcels()->paginate(10);
    }

    /**
     * get single parcel associated by user and parcel id
     *
     * @param User $user
     * @param integer $id
     * @return void
     */
    public function getOneByUser(User $user, int $id)
    {
        return $user->parcels()->where('id', $id)->first();
    }

    /**
     * create parcel for user
     *
     * @param array $data
     * @return Parcel
     */
    public function create(User $user, array $data)
    {
        $parcel = $user->parcels()->create($data);

        event(new ParcelCreated($parcel));

        return $parcel;
    }

    /**
     * get parcel only if available
     *
     * @param integer $id
     * @return Parcel
     */
    public function getIfAvailable(int $id)
    {
        return Parcel::available()->where(['id' => $id])->first();
    }

    /**
     * reserve parcel to not be used by any other biker
     *
     * @param Parcel $parcel
     * @param [type] $data
     * @return void
     */
    public function reserve(Parcel $parcel, $data)
    {
        $parcel->status = ParcelStatus::RESERVED;
        $parcel->expected_pickup_time = Carbon::parse($data['expected_pickup_time']);
        $parcel->expected_dropoff_time = Carbon::parse($data['expected_dropoff_time']);
        $parcel->save();

        return $parcel;
    }

    /**
     * update parcel as picked up
     *
     * @param Parcel $parcel
     * @return Parcel
     */
    public function pickup(Parcel $parcel)
    {
        $parcel->status = ParcelStatus::PICKED_UP;
        $parcel->pickup_time = Carbon::now();
        $parcel->save();

        return $parcel;
    }

    /**
     * update parcel as delivered
     *
     * @param Parcel $parcel
     * @return Parcel
     */
    public function deliver(Parcel $parcel)
    {
        $parcel->status = ParcelStatus::DELIVERED;
        $parcel->dropoff_time = Carbon::now();
        $parcel->save();

        return $parcel;
    }
}
