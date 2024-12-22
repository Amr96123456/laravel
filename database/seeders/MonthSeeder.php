<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $months =[
            ['name'=>'january'],
            ['name'=>'february'],
            ['name'=>'March'],
            ['name'=>'April'],
            ['name'=>'May'],
            ['name'=>'june'],
            ['name'=>'july'],
            ['name'=>'August'],
            ['name'=>'september'],
            ['name'=>'October'],
            ['name'=>'November'],
            ['name'=>'December'],
        ];
        DB::table('month_names')->insert($months);
    }
}
