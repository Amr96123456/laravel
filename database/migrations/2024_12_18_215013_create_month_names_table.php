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
            ['id' => 1, 'name' => 'January'],
            ['id' => 2, 'name' => 'February'],
            ['id' => 3, 'name' => 'March'],
            ['id' => 4, 'name' => 'April'],
            ['id' => 5, 'name' => 'May'],
            ['id' => 6, 'name' => 'June'],
            ['id' => 7, 'name' => 'July'],
            ['id' => 8, 'name' => 'August'],
            ['id' => 9, 'name' => 'September'],
            ['id' => 10, 'name' => 'October'],
            ['id' => 11, 'name' => 'November'],
            ['id' => 12, 'name' => 'December']
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
