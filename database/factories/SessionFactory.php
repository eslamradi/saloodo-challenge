<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Biker;
use App\Models\Parcel;
use App\Models\Session;
use Carbon\Carbon;

class SessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'biker_id' => Biker::factory(),
            'parcel_id' => Parcel::factory(),
            'expected_pickup_time' => function (array $attributes) {
                return Parcel::find($attributes['parcel_id'])->expected_pickup_time;
            },
            'expected_dropoff_time' => function (array $attributes) {
                return Parcel::find($attributes['parcel_id'])->expected_dropoff_time;
            },
            'pickup_time' => function (array $attributes) {
                return Parcel::find($attributes['parcel_id'])->pickup_time;
            },
            'dropoff_time' => function (array $attributes) {
                return Parcel::find($attributes['parcel_id'])->dropoff_time;
            }
        ];
    }
}
