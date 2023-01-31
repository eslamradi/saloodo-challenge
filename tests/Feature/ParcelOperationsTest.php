<?php

namespace Tests\Feature;

use App\Models\Biker;
use App\Models\Parcel;
use App\Models\ParcelStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCaseWithAcceptJson;

class ParcelOperationsTest extends TestCaseWithAcceptJson
{
    use DatabaseTransactions;

    protected $biker;
    protected $otherBiker;

    public function setUp(): void
    {
        parent::setUp();

        $this->biker = Biker::factory()->create();
        $this->otherBiker = Biker::factory()->create();
    }

    public function test_parcel_can_be_reserved_by_only_one_biker()
    {
        $parcel = Parcel::factory()->create();
        $this->actingAs($this->biker, 'biker');

        $response = $this->put(route('parcel.reserve', ['id' => $parcel->id]), [
            'expected_pickup_time' => Carbon::now()->addMinutes(10),
            'expected_dropoff_time' => Carbon::now()->addMinutes(40),
        ]);
        $response->assertStatus(200);

        $this->actingAs($this->otherBiker, 'biker');

        $response = $this->put(route('parcel.reserve', ['id' => $parcel->id]), [
            'expected_pickup_time' => Carbon::now()->addMinutes(10),
            'expected_dropoff_time' => Carbon::now()->addMinutes(40),
        ]);
        $response->assertStatus(400);
    }


    public function test_parcel_is_accessible_by_associated_biker_only()
    {
        $parcel = Parcel::factory()->create();

        $this->actingAs($this->biker, 'biker');
        $response = $this->put(route('parcel.reserve', ['id' => $parcel->id]), [
            'expected_pickup_time' => Carbon::now()->addMinutes(10),
            'expected_dropoff_time' => Carbon::now()->addMinutes(40),
        ]);
        $response->assertStatus(200);

        $this->actingAs($this->otherBiker, 'biker');
        $response = $this->put(route('parcel.pickup', ['id' => $parcel->id]));
        $response->assertStatus(400);

        $this->actingAs($this->biker, 'biker');
        $response = $this->put(route('parcel.pickup', ['id' => $parcel->id]));
        $response->assertStatus(200);
    }

    public function test_parcel_normal_events_excution()
    {
        $parcel = Parcel::factory()->create();
        $this->actingAs($this->biker, 'biker');

        $parcel = Parcel::factory()->create();
        $this->assertTrue($parcel->status == ParcelStatus::wording(ParcelStatus::PENDING));

        // assert parcel is reserved
        $response = $this->put(route('parcel.reserve', ['id' => $parcel->id]), [
            'expected_pickup_time' => Carbon::now()->addMinutes(10),
            'expected_dropoff_time' => Carbon::now()->addMinutes(40),
        ]);
        $response->assertStatus(200);
        $parcel->refresh();
        $this->assertTrue($parcel->status == ParcelStatus::wording(ParcelStatus::RESERVED));


        // assert parcel is picked up
        $response = $this->put(route('parcel.pickup', ['id' => $parcel->id]));
        $response->assertStatus(200);
        $parcel->refresh();
        $this->assertTrue($parcel->status == ParcelStatus::wording(ParcelStatus::PICKED_UP));

        // assert parcel is delivered
        $response = $this->put(route('parcel.deliver', ['id' => $parcel->id]));
        $response->assertStatus(200);
        $parcel->refresh();
        $this->assertTrue($parcel->status == ParcelStatus::wording(ParcelStatus::DELIVERED));
    }
}
