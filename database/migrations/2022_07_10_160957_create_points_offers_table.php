<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_offers', function (Blueprint $table) {
            $table->id();
            $table->integer('points');
            $table->string('description');
            $table->string('terms');
            $table->unsignedBigInteger('idStore');
            $table->foreign('idStore')->references('id')->on('stores')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('idUser')->nullable();
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('idBeacon');
            $table->foreign('idBeacon')->references('id')->on('beacons')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['available', 'deactive', 'consumed', 'expired'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points_offers');
    }
};
