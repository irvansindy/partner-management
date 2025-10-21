<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Provinces;
class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provinces::truncate();
        $query = "INSERT INTO provinces (id, name, latitude, longitude, capital) VALUES
            (1, 'Aceh', 4.6951, 96.7494, 'Banda Aceh'),
            (2, 'Sumatera Utara', 2.1154, 99.5451, 'Medan'),
            (3, 'Sumatera Barat', -0.7399, 100.8000, 'Padang'),
            (4, 'Riau', 0.2933, 101.7068, 'Pekanbaru'),
            (5, 'Kepulauan Riau', 3.9457, 108.1429, 'Tanjung Pinang'),
            (6, 'Jambi', -1.4852, 102.4381, 'Jambi'),
            (7, 'Sumatera Selatan', -3.3194, 103.9140, 'Palembang'),
            (8, 'Bangka Belitung', -2.7411, 106.4406, 'Pangkal Pinang'),
            (9, 'Bengkulu', -3.5778, 102.3463, 'Bengkulu'),
            (10, 'Lampung', -4.5586, 105.4068, 'Bandar Lampung'),
            (11, 'DKI Jakarta', -6.2088, 106.8456, 'Jakarta'),
            (12, 'Banten', -6.4058, 106.0640, 'Serang'),
            (13, 'Jawa Barat', -7.0909, 107.6689, 'Bandung'),
            (14, 'Jawa Tengah', -7.1509, 110.1403, 'Semarang'),
            (15, 'DI Yogyakarta', -7.7956, 110.3695, 'Yogyakarta'),
            (16, 'Jawa Timur', -7.5361, 112.2384, 'Surabaya'),
            (17, 'Bali', -8.4095, 115.1889, 'Denpasar'),
            (18, 'Nusa Tenggara Barat', -8.6529, 117.3616, 'Mataram'),
            (19, 'Nusa Tenggara Timur', -8.6574, 121.0794, 'Kupang'),
            (20, 'Kalimantan Barat', -0.2787, 111.4752, 'Pontianak'),
            (21, 'Kalimantan Tengah', -1.6815, 113.3824, 'Palangka Raya'),
            (22, 'Kalimantan Selatan', -3.0926, 115.2838, 'Banjarmasin'),
            (23, 'Kalimantan Timur', 0.5387, 116.4194, 'Samarinda'),
            (24, 'Kalimantan Utara', 3.0731, 116.0413, 'Tanjung Selor'),
            (25, 'Sulawesi Utara', 0.6246, 123.9750, 'Manado'),
            (26, 'Gorontalo', 0.6999, 122.4467, 'Gorontalo'),
            (27, 'Sulawesi Tengah', -1.4300, 121.4456, 'Palu'),
            (28, 'Sulawesi Barat', -2.8441, 119.2320, 'Mamuju'),
            (29, 'Sulawesi Selatan', -3.6687, 119.9740, 'Makassar'),
            (30, 'Sulawesi Tenggara', -4.1448, 122.1741, 'Kendari'),
            (31, 'Maluku', -3.2385, 130.1453, 'Ambon'),
            (32, 'Maluku Utara', 1.5709, 127.8087, 'Sofifi'),
            (33, 'Papua Barat', -1.3361, 133.1747, 'Manokwari'),
            (34, 'Papua', -4.2699, 138.0804, 'Jayapura'),
            (35, 'Papua Tengah', -3.9700, 136.2700, 'Nabire'),
            (36, 'Papua Pegunungan', -3.9800, 138.9500, 'Jayawijaya'),
            (37, 'Papua Selatan', -6.0800, 140.5000, 'Merauke'),
            (38, 'Papua Barat Daya', -2.5300, 132.5300, 'Sorong');";
        DB::unprepared($query);
        // DB::table('provinces')->insert([
        //     ['id' => '11', 'name' => 'ACEH'],
        //     ['id' => '12', 'name' => 'SUMATERA UTARA'],
        //     ['id' => '13', 'name' => 'SUMATERA BARAT'],
        //     ['id' => '14', 'name' => 'RIAU'],
        //     ['id' => '15', 'name' => 'JAMBI'],
        //     ['id' => '16', 'name' => 'SUMATERA SELATAN'],
        //     ['id' => '17', 'name' => 'BENGKULU'],
        //     ['id' => '18', 'name' => 'LAMPUNG'],
        //     ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
        //     ['id' => '21', 'name' => 'KEPULAUAN RIAU'],
        //     ['id' => '31', 'name' => 'DKI JAKARTA'],
        //     ['id' => '32', 'name' => 'JAWA BARAT'],
        //     ['id' => '33', 'name' => 'JAWA TENGAH'],
        //     ['id' => '34', 'name' => 'DAERAH ISTIMEWA YOGYAKARTA'],
        //     ['id' => '35', 'name' => 'JAWA TIMUR'],
        //     ['id' => '36', 'name' => 'BANTEN'],
        //     ['id' => '51', 'name' => 'BALI'],
        //     ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
        //     ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
        //     ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
        //     ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
        //     ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
        //     ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
        //     ['id' => '65', 'name' => 'KALIMANTAN UTARA'],
        //     ['id' => '71', 'name' => 'SULAWESI UTARA'],
        //     ['id' => '72', 'name' => 'SULAWESI TENGAH'],
        //     ['id' => '73', 'name' => 'SULAWESI SELATAN'],
        //     ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
        //     ['id' => '75', 'name' => 'GORONTALO'],
        //     ['id' => '76', 'name' => 'SULAWESI BARAT'],
        //     ['id' => '81', 'name' => 'MALUKU'],
        //     ['id' => '82', 'name' => 'MALUKU UTARA'],
        //     ['id' => '91', 'name' => 'PAPUA'],
        //     ['id' => '92', 'name' => 'PAPUA BARAT'],
        //     ['id' => '93', 'name' => 'PAPUA SELATAN'],
        //     ['id' => '94', 'name' => 'PAPUA TENGAH'],
        //     ['id' => '95', 'name' => 'PAPUA PEGUNUNGAN'],
        // ]);
    }
}
