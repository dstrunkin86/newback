<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('artwork_id')->index();

            $table->float('artwork_price')->nullable();
            $table->float('insurance_price')->nullable();
            $table->float('delivery_price')->nullable();
            $table->float('total_price')->nullable();
            $table->enum('currency',['RUB','USD'])->default('RUB');

            $table->enum('status',['new','delivery_created','payment_created','hold','accepted_by_artist','courier','paid','delivered','cancelled_by_artist','cancelled_by_user','cancelled_by_system'])->default('new');

            $table->json('recepient_address');
            $table->json('recepient_contact');

            $table->string('delivery_system',10)->nullable();
            $table->string('delivery_option',10)->nullable();
            $table->string('delivery_id')->nullable();

            $table->boolean('insurance')->default(false);

            $table->string('payment_system',10)->nullable();
            $table->string('payment_id')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
