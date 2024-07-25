<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NumbersTableSeeder extends Seeder
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
                    'number' => '1人',
                ],
                [
                    'number' => '2人',
                ],
                [
                    'number' => '3人',
                ],
                [
                    'number' => '4人',
                ],
                [
                    'number' => '5人',
                ],
                [
                    'number' => '6人',
                ],
                [
                    'number' => '7人',
                ],
                [
                    'number' => '8人',
                ],
                [
                    'number' => '9人',
                ],
                [
                    'number' => '10人',
                ],
                [
                    'number' => '11人',
                ],
                [
                    'number' => '12人',
                ],
                [
                    'number' => '13人',
                ],
                [
                    'number' => '14人',
                ],
                [
                    'number' => '15人',
                ],
                [
                    'number' => '16人',
                ],
                [
                    'number' => '17人',
                ],
                [
                    'number' => '18人',
                ],
                [
                    'number' => '19人',
                ],
                [
                    'number' => '20人',
                ],
                [
                    'number' => '21人',
                ],
                [
                    'number' => '22人',
                ],
                [
                    'number' => '23人',
                ],
                [
                    'number' => '24人',
                ],
                [
                    'number' => '25人',
                ],
                [
                    'number' => '26人',
                ],
                [
                    'number' => '27人',
                ],
                [
                    'number' => '28人',
                ],
                [
                    'number' => '29人',
                ],
                [
                    'number' => '30人',
                ],
                [
                    'number' => '31人',
                ],
                [
                    'number' => '32人',
                ],
                [
                    'number' => '33人',
                ],
                [
                    'number' => '34人',
                ],
                [
                    'number' => '35人',
                ],
                [
                    'number' => '36人',
                ],
                [
                    'number' => '37人',
                ],
                [
                    'number' => '38人',
                ],
                [
                    'number' => '39人',
                ],
                [
                    'number' => '40人',
                ],
                [
                    'number' => '41人',
                ],
                [
                    'number' => '42人',
                ],
                [
                    'number' => '43人',
                ],
                [
                    'number' => '44人',
                ],
                [
                    'number' => '45人',
                ],
                [
                    'number' => '46人',
                ],
                [
                    'number' => '47人',
                ],
                [
                    'number' => '48人',
                ],
                [
                    'number' => '49人',
                ],
                [
                    'number' => '50人',
                ],
                [
                    'number' => '51人',
                ],
                [
                    'number' => '52人',
                ],
                [
                    'number' => '53人',
                ],
                [
                    'number' => '54人',
                ],
                [
                    'number' => '55人',
                ],
                [
                    'number' => '56人',
                ],
                [
                    'number' => '57人',
                ],
                [
                    'number' => '58人',
                ],
                [
                    'number' => '59人',
                ],
                [
                    'number' => '60人',
                ],
                [
                    'number' => '61人',
                ],
                [
                    'number' => '62人',
                ],
                [
                    'number' => '63人',
                ],
                [
                    'number' => '64人',
                ],
                [
                    'number' => '65人',
                ],
                [
                    'number' => '66人',
                ],
                [
                    'number' => '67人',
                ],
                [
                    'number' => '68人',
                ],
                [
                    'number' => '69人',
                ],
                [
                    'number' => '70人',
                ],
                [
                    'number' => '71人',
                ],
                [
                    'number' => '72人',
                ],
                [
                    'number' => '73人',
                ],
                [
                    'number' => '74人',
                ],
                [
                    'number' => '75人',
                ],
                [
                    'number' => '76人',
                ],
                [
                    'number' => '77人',
                ],
                [
                    'number' => '78人',
                ],
                [
                    'number' => '79人',
                ],
                [
                    'number' => '80人',
                ],
                [
                    'number' => '81人',
                ],
                [
                    'number' => '82人',
                ],
                [
                    'number' => '83人',
                ],
                [
                    'number' => '84人',
                ],
                [
                    'number' => '85人',
                ],
                [
                    'number' => '86人',
                ],
                [
                    'number' => '87人',
                ],
                [
                    'number' => '88人',
                ],
                [
                    'number' => '89人',
                ],
                [
                    'number' => '90人',
                ],
                [
                    'number' => '91人',
                ],
                [
                    'number' => '92人',
                ],
                [
                    'number' => '93人',
                ],
                [
                    'number' => '94人',
                ],
                [
                    'number' => '95人',
                ],
                [
                    'number' => '96人',
                ],
                [
                    'number' => '97人',
                ],
                [
                    'number' => '98人',
                ],
                [
                    'number' => '99人',
                ],
            ];
        foreach ($params as $param) {
            $param['created_at'] = Carbon::now();
            $param['updated_at'] = Carbon::now();
            DB::table('numbers')->insert($param);
        }
    }
}
