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
        Schema::create('beacons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idStore');
            $table->foreign('idStore')->references('id')->on('stores')->onDelete('cascade')->onUpdate('cascade');
            $table->string('identifier');
            $table->enum('status', ['active', 'deactive'])->default('active');
            $table->string('proximityUUID')->unique();
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
        Schema::dropIfExists('beacons');
    }
};
