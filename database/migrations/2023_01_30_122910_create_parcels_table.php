<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('pickup_address');
            $table->text('delivery_address');
            $table->integer('status');
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('biker_id')->constrained('users');
            $table->text('description')->nullable();
            $table->timestamp('expected_pickup_time')->nullable();
            $table->timestamp('expected_dropoff_time')->nullable();
            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('dropoff_time')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('parcels');
    }
}
