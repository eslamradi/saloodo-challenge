<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biker_id')->constrained('users');
            $table->foreignId('parcel_id')->constrained();
            $table->timestamp('expected_pickup_time')->nullable();
            $table->timestamp('expected_dropoff_time')->nullable();
            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('dropoff_time')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
