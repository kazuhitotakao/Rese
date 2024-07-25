<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params =
            [
                [
                    'time' => '10:00',
                ],
                [
                    'time' => '10:15',
                ],
                [
                    'time' => '10:30',
                ],
                [
                    'time' => '10:45',
                ],
                [
                    'time' => '11:00',
                ],
                [
                    'time' => '11:15',
                ],
                [
                    'time' => '11:30',
                ],
                [
                    'time' => '11:45',
                ],
                [
                    'time' => '12:00',
                ],
                [
                    'time' => '12:15',
                ],
                [
                    'time' => '12:30',
                ],
                [
                    'time' => '12:45',
                ],
                [
                    'time' => '13:00',
                ],
                [
                    'time' => '13:15',
                ],
                [
                    'time' => '13:30',
                ],
                [
                    'time' => '13:45',
                ],
                [
                    'time' => '14:00',
                ],
                [
                    'time' => '14:15',
                ],
                [
                    'time' => '14:30',
                ],
                [
                    'time' => '14:45',
                ],
                [
                    'time' => '15:00',
                ],
                [
                    'time' => '15:15',
                ],
                [
                    'time' => '15:30',
                ],
                [
                    'time' => '15:45',
                ],
                [
                    'time' => '16:00',
                ],
                [
                    'time' => '16:15',
                ],
                [
                    'time' => '16:30',
                ],
                [
                    'time' => '16:45',
                ],
                [
                    'time' => '17:00',
                ],
                [
                    'time' => '17:15',
                ],
                [
                    'time' => '17:30',
                ],
                [
                    'time' => '17:45',
                ],
                [
                    'time' => '18:00',
                ],
                [
                    'time' => '18:15',
                ],
                [
                    'time' => '18:30',
                ],
                [
                    'time' => '18:45',
                ],
                [
                    'time' => '19:00',
                ],
                [
                    'time' => '19:15',
                ],
                [
                    'time' => '19:30',
                ],
                [
                    'time' => '19:45',
                ],
                [
                    'time' => '20:00',
                ],
                [
                    'time' => '20:15',
                ],
                [
                    'time' => '20:30',
                ],
                [
                    'time' => '20:45',
                ],
                [
                    'time' => '21:00',
                ],
                [
                    'time' => '21:15',
                ],
                [
                    'time' => '21:30',
                ],
                [
                    'time' => '21:45',
                ],
                [
                    'time' => '22:00',
                ],
                [
                    'time' => '22:15',
                ],
                [
                    'time' => '22:30',
                ],
                [
                    'time' => '22:45',
                ],
                [
                    'time' => '23:00',
                ],
                [
                    'time' => '23:15',
                ],
                [
                    'time' => '23:30',
                ],
                [
                    'time' => '23:45',
                ],
                [
                    'time' => '24:00',
                ],
            ];
        foreach ($params as $param) {
            $param['created_at'] = Carbon::now();
            $param['updated_at'] = Carbon::now();
            DB::table('times')->insert($param);
        }
    }
}
