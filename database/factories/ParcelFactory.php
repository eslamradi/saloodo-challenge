<?php

namespace Database\Factories;

use App\Models\Biker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Parcel;
use App\Models\ParcelStatus;
use Carbon\Carbon;

class ParcelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Parcel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->text,
            'pickup_address' => $this->faker->sentence(4),
            'delivery_address' => $this->faker->sentence(4),
            'customer_id' => Customer::factory(),
            'status' => ParcelStatus::PENDING,
        ];
    }

    public function reserved()
    {
        $now = Carbon::now();
        return $this->state(fn (array $attributes) => [
            'status' => ParcelStatus::RESERVED,
            'biker_id' => Biker::factory(),
            'expected_pickup_time' => $now->addMinutes(3)->toDateTime(),
            'expected_dropoff_time' => $now->addMinutes(30)->toDateTime(),
        ]);
    }

    public function pickedUp()
    {
        $now = Carbon::now();
        return $this->state(fn (array $attributes) => [
            'status' => ParcelStatus::PICKED_UP,
            'biker_id' => Biker::factory(),
            'expected_pickup_time' => $now->addMinutes(3)->toDateTime(),
            'pickup_time' => $now->addMinutes(5)->toDateTime(),
            'expected_dropoff_time' => $now->addMinutes(30)->toDateTime(),
        ]);
    }

    public function delivered()
    {
        $now = Carbon::now();
        return $this->state(fn (array $attributes) => [
            'status' => ParcelStatus::DELIVERED,
            'biker_id' => Biker::factory(),
            'expected_pickup_time' => $now->addMinutes(3)->toDateTime(),
            'pickup_time' => $now->addMinutes(5)->toDateTime(),
            'expected_dropoff_time' => $now->addMinutes(30)->toDateTime(),
            'dropoff_time' => $now->addMinutes(5)->toDateTime(),
        ]);
    }
}
