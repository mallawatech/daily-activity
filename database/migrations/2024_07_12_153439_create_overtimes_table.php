<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('report_id')->unsigned(); // Pastikan kolom ini ada
            $table->date('date');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('activity_log');
            $table->decimal('total_overtime', 8, 2); // Ubah tipe data menjadi decimal
            $table->json('photos')->nullable();
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['report_id']);
        });
        Schema::dropIfExists('overtimes');
    }
}
