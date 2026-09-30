<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();

            // Gold & Silver rates
            $table->decimal('today22', 10, 2)->default(0);
            $table->decimal('today24', 10, 2)->default(0);
            $table->decimal('silver_cost', 10, 2)->default(0);

            // Optional: who updated the rate
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
