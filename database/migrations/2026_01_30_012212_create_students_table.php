<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();

        $table->string('student_name');
        $table->date('dob')->nullable();

        $table->integer('kind'); // 22 or 24
        $table->string('type');  // GG / SS

        $table->integer('quantity'); // number of grams / items

        $table->decimal('cost', 10, 2); // paid amount
        $table->decimal('reminder', 10, 2)->nullable();

        $table->string('phone');
        $table->text('address');

        $table->timestamps();
    });
}

};
