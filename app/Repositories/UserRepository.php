<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{
    /**
     * create a user instance
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function create($data)
    {
        $model = "\\App\\Models\\" . ucfirst($data['role']);
        if (class_exists($model)) {
            return $model::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);
        }

        throw new Error("Class {$model} does n ot exist");
    }

    /**
     * create a user with customer role
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createCustomer($data)
    {
        return $this->create(array_merge($data, [
            'role' => Role::CUSTOMER
        ]));
    }

    /**
     * create a user with biker role
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createBiker($data)
    {
        return $this->create(array_merge($data, [
            'role' => Role::BIKER
        ]));
    }
}
