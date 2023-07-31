<?php


namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
$url_province = "https://api.rajaongkir.com/starter/province?key=edf9775869cb0ca6dbada632fd337177";
$json_str = file_get_contents($url_province);
$json_obj = json_decode($json_str);
$provinces = [];
foreach($json_obj->rajaongkir->results as $province){
$provinces[] = [
'id' => $province->province_id,
'province' => $province->province
];
}
DB::table('provinces')->insert($provinces);
}
}
