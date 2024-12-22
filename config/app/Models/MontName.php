<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // إضافة أسماء الأشهر إلى الجدول
        DB::table('month_names')->insert([
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('month_names');
    }
}
