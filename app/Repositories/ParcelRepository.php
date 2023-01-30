<?php

namespace App\Repositories;

use App\Events\Parcel\ParcelCreated;
use App\Models\User;

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
}
