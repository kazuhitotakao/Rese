<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresTableSeeder extends Seeder
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
                    'name' => '寿司',
                ],
                [
                    'name' => '焼肉',
                ],
                [
                    'name' => '居酒屋',
                ],
                [
                    'name' => 'イタリアン',
                ],
                [
                    'name' => 'ラーメン',
                ],
            ];
        foreach ($params as $param) {
            $param['created_at'] = Carbon::now();
            $param['updated_at'] = Carbon::now();
            DB::table('genres')->insert($param);
        }
    }
}
