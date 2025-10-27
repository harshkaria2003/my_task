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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
        $table->string('title');
        $table->text('description');
        $table->decimal('price', 8, 2);
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
        Schema::dropIfExists('courses');
    }
};
