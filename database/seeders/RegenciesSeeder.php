<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Regencies;
class RegenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Regencies::truncate();
        $query = "-- ACEH
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (1, 'Banda Aceh', 5.5483, 95.3238),
            (1, 'Langsa', 4.4683, 97.9683),
            (1, 'Lhokseumawe', 5.1801, 97.1507),
            (1, 'Sabang', 5.8947, 95.3194),
            (1, 'Subulussalam', 2.6833, 97.9500),
            (1, 'Aceh Barat', 4.4500, 96.1667),
            (1, 'Aceh Barat Daya', 3.8000, 96.8500),
            (1, 'Aceh Besar', 5.4000, 95.5000),
            (1, 'Aceh Jaya', 4.8333, 95.6500),
            (1, 'Aceh Selatan', 3.2167, 97.4167),
            (1, 'Aceh Singkil', 2.3167, 97.9333),
            (1, 'Aceh Tamiang', 4.2500, 98.1667),
            (1, 'Aceh Tengah', 4.5833, 96.8333),
            (1, 'Aceh Tenggara', 3.5333, 97.7500),
            (1, 'Aceh Timur', 4.6333, 97.6333),
            (1, 'Aceh Utara', 5.1667, 97.0000),
            (1, 'Bener Meriah', 4.7833, 96.8333),
            (1, 'Bireuen', 5.2000, 96.7000),
            (1, 'Gayo Lues', 3.9667, 97.3833),
            (1, 'Nagan Raya', 4.1500, 96.4500),
            (1, 'Pidie', 5.1333, 96.1500),
            (1, 'Pidie Jaya', 5.1167, 96.1833),
            (1, 'Simeulue', 2.6167, 96.0833);

            -- SUMATERA UTARA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (2, 'Medan', 3.5952, 98.6722),
            (2, 'Binjai', 3.6000, 98.4833),
            (2, 'Padangsidimpuan', 1.3833, 99.2667),
            (2, 'Pematangsiantar', 2.9667, 99.0500),
            (2, 'Tebing Tinggi', 3.3283, 99.1628),
            (2, 'Tanjungbalai', 2.9667, 99.8000),
            (2, 'Gunungsitoli', 1.2881, 97.6144),
            (2, 'Asahan', 2.9833, 99.6167),
            (2, 'Batubara', 2.7167, 99.7167),
            (2, 'Dairi', 2.6667, 98.3333),
            (2, 'Deli Serdang', 3.4500, 98.8833),
            (2, 'Humbang Hasundutan', 2.2667, 98.5000),
            (2, 'Karo', 3.1333, 98.5000),
            (2, 'Labuhanbatu', 2.1833, 99.8167),
            (2, 'Labuhanbatu Selatan', 1.8500, 100.1333),
            (2, 'Labuhanbatu Utara', 2.3833, 99.8167),
            (2, 'Langkat', 3.7833, 98.3000),
            (2, 'Mandailing Natal', 0.7833, 99.3333),
            (2, 'Nias', 1.0833, 97.5167),
            (2, 'Nias Barat', 1.1333, 97.4667),
            (2, 'Nias Selatan', 0.6167, 97.7500),
            (2, 'Nias Utara', 1.3500, 97.4500),
            (2, 'Padang Lawas', 1.5500, 99.8167),
            (2, 'Padang Lawas Utara', 1.8833, 99.7333),
            (2, 'Pakpak Bharat', 2.5833, 98.2500),
            (2, 'Samosir', 2.5667, 98.7167),
            (2, 'Serdang Bedagai', 3.3667, 99.1333),
            (2, 'Simalungun', 2.9667, 99.0167),
            (2, 'Tapanuli Selatan', 1.5333, 99.1667),
            (2, 'Tapanuli Tengah', 1.8833, 98.5667),
            (2, 'Tapanuli Utara', 2.0167, 99.0667),
            (2, 'Toba Samosir', 2.4667, 99.0833);

            -- SUMATERA BARAT
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (3, 'Padang', -0.9471, 100.4172),
            (3, 'Bukittinggi', -0.3056, 100.3694),
            (3, 'Payakumbuh', -0.2333, 100.6333),
            (3, 'Padang Panjang', -0.4667, 100.4000),
            (3, 'Sawahlunto', -0.6833, 100.7667),
            (3, 'Solok', -0.7917, 100.6556),
            (3, 'Pariaman', -0.6167, 100.1167),
            (3, 'Agam', -0.2500, 100.1667),
            (3, 'Dharmasraya', -1.1500, 101.5500),
            (3, 'Kepulauan Mentawai', -2.0833, 99.6500),
            (3, 'Lima Puluh Kota', -0.0500, 100.5500),
            (3, 'Padang Pariaman', -0.5667, 100.1000),
            (3, 'Pasaman', 0.3333, 99.9167),
            (3, 'Pasaman Barat', 0.1000, 99.5833),
            (3, 'Pesisir Selatan', -1.7000, 100.8833),
            (3, 'Sijunjung', -0.6833, 101.0167),
            (3, 'Solok', -1.0167, 100.8000),
            (3, 'Solok Selatan', -1.4500, 101.2833),
            (3, 'Tanah Datar', -0.5000, 100.5667);

            -- RIAU
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (4, 'Pekanbaru', 0.5333, 101.4500),
            (4, 'Dumai', 1.6833, 101.4333),
            (4, 'Bengkalis', 1.4667, 102.1667),
            (4, 'Indragiri Hilir', -0.5833, 103.0000),
            (4, 'Indragiri Hulu', -0.6333, 102.3333),
            (4, 'Kampar', 0.3167, 101.1500),
            (4, 'Kepulauan Meranti', 1.0000, 102.8333),
            (4, 'Kuantan Singingi', -0.4833, 101.4667),
            (4, 'Pelalawan', 0.3167, 101.8333),
            (4, 'Rokan Hilir', 2.0833, 100.8833),
            (4, 'Rokan Hulu', 0.8333, 100.5000),
            (4, 'Siak', 0.8000, 102.0000);

            -- KEPULAUAN RIAU
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (5, 'Tanjung Pinang', 0.9167, 104.4500),
            (5, 'Batam', 1.0456, 104.0305),
            (5, 'Bintan', 1.0833, 104.5000),
            (5, 'Karimun', 0.8333, 103.4167),
            (5, 'Kepulauan Anambas', 3.0333, 106.1000),
            (5, 'Lingga', -0.2000, 104.6167),
            (5, 'Natuna', 3.9667, 108.2500);

            -- JAMBI
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (6, 'Jambi', -1.6101, 103.6131),
            (6, 'Sungai Penuh', -2.0631, 101.3919),
            (6, 'Batang Hari', -1.7500, 103.9167),
            (6, 'Bungo', -1.5833, 102.0833),
            (6, 'Kerinci', -1.9667, 101.5000),
            (6, 'Merangin', -2.1667, 101.9167),
            (6, 'Muaro Jambi', -1.5000, 103.8833),
            (6, 'Sarolangun', -2.2667, 102.6167),
            (6, 'Tanjung Jabung Barat', -1.1167, 103.4167),
            (6, 'Tanjung Jabung Timur', -1.0500, 103.8833),
            (6, 'Tebo', -1.4333, 102.4500);

            -- SUMATERA SELATAN
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (7, 'Palembang', -2.9761, 104.7754),
            (7, 'Prabumulih', -3.4333, 104.2333),
            (7, 'Pagar Alam', -4.0333, 103.2500),
            (7, 'Lubuklinggau', -3.2944, 102.8597),
            (7, 'Banyuasin', -2.7500, 104.8333),
            (7, 'Empat Lawang', -3.6833, 102.7500),
            (7, 'Lahat', -3.7833, 103.5333),
            (7, 'Muara Enim', -3.7167, 103.9333),
            (7, 'Musi Banyuasin', -2.5833, 104.2500),
            (7, 'Musi Rawas', -3.0000, 103.0833),
            (7, 'Musi Rawas Utara', -2.4833, 102.6333),
            (7, 'Ogan Ilir', -3.3500, 104.7667),
            (7, 'Ogan Komering Ilir', -3.2667, 105.2500),
            (7, 'Ogan Komering Ulu', -4.3333, 104.3333),
            (7, 'Ogan Komering Ulu Selatan', -4.8667, 104.1500),
            (7, 'Ogan Komering Ulu Timur', -4.1167, 104.7833),
            (7, 'Penukal Abab Lematang Ilir', -3.5333, 103.8167);

            -- BANGKA BELITUNG
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (8, 'Pangkal Pinang', -2.1333, 106.1167),
            (8, 'Bangka', -2.0667, 106.0000),
            (8, 'Bangka Barat', -1.8667, 105.6667),
            (8, 'Bangka Selatan', -2.8333, 106.5000),
            (8, 'Bangka Tengah', -2.3167, 106.2500),
            (8, 'Belitung', -2.7667, 107.6500),
            (8, 'Belitung Timur', -2.9167, 108.2333);

            -- BENGKULU
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (9, 'Bengkulu', -3.8004, 102.2655),
            (9, 'Bengkulu Selatan', -4.4167, 103.0833),
            (9, 'Bengkulu Tengah', -3.5667, 102.4333),
            (9, 'Bengkulu Utara', -3.5000, 101.9167),
            (9, 'Kaur', -4.6167, 103.4167),
            (9, 'Kepahiang', -3.6333, 102.5833),
            (9, 'Lebong', -3.2000, 102.4167),
            (9, 'Mukomuko', -2.5833, 101.1500),
            (9, 'Rejang Lebong', -3.4667, 102.7333),
            (9, 'Seluma', -4.0500, 102.5333);

            -- LAMPUNG
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (10, 'Bandar Lampung', -5.4292, 105.2619),
            (10, 'Metro', -5.1131, 105.3067),
            (10, 'Lampung Barat', -5.0167, 104.1833),
            (10, 'Lampung Selatan', -5.5833, 105.5000),
            (10, 'Lampung Tengah', -4.8500, 105.2500),
            (10, 'Lampung Timur', -4.8500, 105.7333),
            (10, 'Lampung Utara', -4.7500, 104.7500),
            (10, 'Mesuji', -3.9167, 105.5000),
            (10, 'Pesawaran', -5.4000, 105.0333),
            (10, 'Pesisir Barat', -5.2500, 104.0000),
            (10, 'Pringsewu', -5.3667, 104.9833),
            (10, 'Tanggamus', -5.4667, 104.6333),
            (10, 'Tulang Bawang', -4.4333, 105.6167),
            (10, 'Tulang Bawang Barat', -4.6333, 105.1500),
            (10, 'Way Kanan', -4.3167, 104.5167);

            -- DKI JAKARTA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (11, 'Jakarta Pusat', -6.1862, 106.8063),
            (11, 'Jakarta Utara', -6.1388, 106.8827),
            (11, 'Jakarta Barat', -6.1670, 106.7590),
            (11, 'Jakarta Selatan', -6.2615, 106.8106),
            (11, 'Jakarta Timur', -6.2250, 106.9004),
            (11, 'Kepulauan Seribu', -5.6333, 106.5667);

            -- BANTEN
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (12, 'Serang', -6.1203, 106.1503),
            (12, 'Tangerang', -6.1783, 106.6319),
            (12, 'Tangerang Selatan', -6.2862, 106.7139),
            (12, 'Cilegon', -6.0025, 106.0186),
            (12, 'Lebak', -6.5667, 106.2500),
            (12, 'Pandeglang', -6.3083, 106.1058),
            (12, 'Kabupaten Serang', -6.2833, 106.1500),
            (12, 'Kabupaten Tangerang', -6.1667, 106.6333);

            -- JAWA BARAT
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (13, 'Bandung', -6.9175, 107.6191),
            (13, 'Bekasi', -6.2383, 106.9756),
            (13, 'Bogor', -6.5971, 106.8060),
            (13, 'Cimahi', -6.8722, 107.5419),
            (13, 'Cirebon', -6.7063, 108.5571),
            (13, 'Depok', -6.4025, 106.7942),
            (13, 'Sukabumi', -6.9278, 106.9269),
            (13, 'Tasikmalaya', -7.3506, 108.2167),
            (13, 'Banjar', -7.3667, 108.5333),
            (13, 'Bandung Barat', -6.8667, 107.4833),
            (13, 'Kabupaten Bandung', -7.0667, 107.5167),
            (13, 'Kabupaten Bekasi', -6.2500, 107.1500),
            (13, 'Kabupaten Bogor', -6.5833, 106.8000),
            (13, 'Ciamis', -7.3167, 108.3500),
            (13, 'Cianjur', -6.8167, 107.1333),
            (13, 'Kabupaten Cirebon', -6.7833, 108.4833),
            (13, 'Garut', -7.2167, 107.9000),
            (13, 'Indramayu', -6.3333, 108.3333),
            (13, 'Karawang', -6.3167, 107.3000),
            (13, 'Kuningan', -6.9833, 108.4833),
            (13, 'Majalengka', -6.8333, 108.2167),
            (13, 'Pangandaran', -7.6833, 108.6500),
            (13, 'Purwakarta', -6.5500, 107.4333),
            (13, 'Subang', -6.5667, 107.7667),
            (13, 'Kabupaten Sukabumi', -6.9333, 106.9333),
            (13, 'Sumedang', -6.8500, 107.9167),
            (13, 'Kabupaten Tasikmalaya', -7.5333, 108.2000);

            -- JAWA TENGAH
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (14, 'Semarang', -6.9667, 110.4167),
            (14, 'Magelang', -7.4797, 110.2172),
            (14, 'Pekalongan', -6.8886, 109.6753),
            (14, 'Salatiga', -7.3311, 110.4928),
            (14, 'Surakarta', -7.5561, 110.8316),
            (14, 'Tegal', -6.8694, 109.1406),
            (14, 'Banjarnegara', -7.3667, 109.6833),
            (14, 'Banyumas', -7.5167, 109.2667),
            (14, 'Batang', -6.9167, 109.7333),
            (14, 'Blora', -6.9667, 111.4167),
            (14, 'Boyolali', -7.5333, 110.5833),
            (14, 'Brebes', -6.8833, 109.0333),
            (14, 'Cilacap', -7.7167, 109.0167),
            (14, 'Demak', -6.8917, 110.6389),
            (14, 'Grobogan', -7.0333, 110.9167),
            (14, 'Jepara', -6.5833, 110.6667),
            (14, 'Karanganyar', -7.6000, 110.9500),
            (14, 'Kebumen', -7.6667, 109.6667),
            (14, 'Kendal', -7.0000, 110.2000),
            (14, 'Klaten', -7.7167, 110.6000),
            (14, 'Kudus', -6.8000, 110.8333),
            (14, 'Kabupaten Magelang', -7.4833, 110.2167),
            (14, 'Kabupaten Pekalongan', -6.9333, 109.6500),
            (14, 'Pemalang', -6.8833, 109.3833),
            (14, 'Purbalingga', -7.3833, 109.3667),
            (14, 'Purworejo', -7.7167, 110.0000),
            (14, 'Rembang', -6.7167, 111.3500),
            (14, 'Semarang', -7.3167, 110.5000),
            (14, 'Sragen', -7.4333, 111.0167),
            (14, 'Sukoharjo', -7.6833, 110.8333),
            (14, 'Kabupaten Tegal', -7.0000, 109.1333),
            (14, 'Temanggung', -7.3167, 110.1667),
            (14, 'Wonogiri', -7.8167, 110.9167),
            (14, 'Wonosobo', -7.3667, 109.9000);

            -- DI YOGYAKARTA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (15, 'Yogyakarta', -7.7956, 110.3695),
            (15, 'Bantul', -7.8883, 110.3289),
            (15, 'Gunungkidul', -7.9167, 110.6000),
            (15, 'Kulon Progo', -7.8333, 110.1667),
            (15, 'Sleman', -7.7167, 110.3500);

            -- JAWA TIMUR
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (16, 'Surabaya', -7.2575, 112.7521),
            (16, 'Malang', -7.9797, 112.6304),
            (16, 'Kediri', -7.8167, 112.0167),
            (16, 'Blitar', -8.0997, 112.1681),
            (16, 'Madiun', -7.6297, 111.5239),
            (16, 'Mojokerto', -7.4664, 112.4339),
            (16, 'Pasuruan', -7.6453, 112.9075),
            (16, 'Probolinggo', -7.7543, 113.2159),
            (16, 'Batu', -7.8667, 112.5333),
            (16, 'Bangkalan', -7.0333, 112.7500),
            (16, 'Banyuwangi', -8.2189, 114.3692),
            (16, 'Kabupaten Blitar', -8.1000, 112.3000),
            (16, 'Bojonegoro', -7.1500, 111.8833),
            (16, 'Bondowoso', -7.9333, 113.8167),
            (16, 'Gresik', -7.1667, 112.6500),
            (16, 'Jember', -8.1667, 113.7000),
            (16, 'Jombang', -7.5500, 112.2333),
            (16, 'Kabupaten Kediri', -7.8333, 112.0167),
            (16, 'Lamongan', -7.1167, 112.4167),
            (16, 'Lumajang', -8.1333, 113.2167),
            (16, 'Kabupaten Madiun', -7.6333, 111.6667),
            (16, 'Magetan', -7.6500, 111.3500),
            (16, 'Kabupaten Malang', -8.1667, 112.7000),
            (16, 'Kabupaten Mojokerto', -7.5500, 112.6000),
            (16, 'Nganjuk', -7.6000, 111.9000),
            (16, 'Ngawi', -7.4000, 111.4500),
            (16, 'Pacitan', -8.2000, 111.1000),
            (16, 'Pamekasan', -7.1667, 113.4833),
            (16, 'Kabupaten Pasuruan', -7.7333, 112.9000),
            (16, 'Ponorogo', -7.8667, 111.4667),
            (16, 'Kabupaten Probolinggo', -7.8833, 113.3667),
            (16, 'Sampang', -7.1833, 113.2500),
            (16, 'Sidoarjo', -7.4500, 112.7167),
            (16, 'Situbondo', -7.7167, 114.0167),
            (16, 'Sumenep', -7.0167, 113.8667),
            (16, 'Trenggalek', -8.0500, 111.7167),
            (16, 'Tuban', -6.9000, 112.0500),
            (16, 'Tulungagung', -8.0667, 111.9000);

            -- BALI
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (17, 'Denpasar', -8.6705, 115.2126),
            (17, 'Badung', -8.5500, 115.1667),
            (17, 'Bangli', -8.4500, 115.3500),
            (17, 'Buleleng', -8.1167, 115.0833),
            (17, 'Gianyar', -8.5333, 115.3333),
            (17, 'Jembrana', -8.3667, 114.6667),
            (17, 'Karangasem', -8.4500, 115.6167),
            (17, 'Klungkung', -8.5333, 115.4000),
            (17, 'Tabanan', -8.5333, 115.1167);

            -- NUSA TENGGARA BARAT
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (18, 'Mataram', -8.5833, 116.1167),
            (18, 'Bima', -8.4667, 118.7167),
            (18, 'Kabupaten Bima', -8.5333, 118.8000),
            (18, 'Dompu', -8.5333, 118.4667),
            (18, 'Lombok Barat', -8.6500, 116.1167),
            (18, 'Lombok Tengah', -8.7000, 116.2833),
            (18, 'Lombok Timur', -8.5667, 116.5167),
            (18, 'Lombok Utara', -8.3000, 116.4000),
            (18, 'Sumbawa', -8.5000, 117.4167),
            (18, 'Sumbawa Barat', -8.8667, 116.9333);

            -- NUSA TENGGARA TIMUR
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (19, 'Kupang', -10.1718, 123.6075),
            (19, 'Alor', -8.2167, 124.5667),
            (19, 'Belu', -9.2500, 124.9167),
            (19, 'Ende', -8.8500, 121.6667),
            (19, 'Flores Timur', -8.2833, 122.9667),
            (19, 'Kabupaten Kupang', -10.0000, 123.9167),
            (19, 'Lembata', -8.4000, 123.5167),
            (19, 'Manggarai', -8.6000, 120.4500),
            (19, 'Manggarai Barat', -8.6667, 120.0667),
            (19, 'Manggarai Timur', -8.6333, 120.7500),
            (19, 'Nagekeo', -8.7667, 121.3333),
            (19, 'Ngada', -8.6500, 120.9833),
            (19, 'Rote Ndao', -10.7500, 123.1000),
            (19, 'Sabu Raijua', -10.5167, 121.8500),
            (19, 'Sikka', -8.6667, 122.2000),
            (19, 'Sumba Barat', -9.4500, 119.4000),
            (19, 'Sumba Barat Daya', -9.5667, 119.0833),
            (19, 'Sumba Tengah', -9.6333, 119.6667),
            (19, 'Sumba Timur', -9.7667, 120.3000),
            (19, 'Timor Tengah Selatan', -9.8833, 124.3667),
            (19, 'Timor Tengah Utara', -9.3333, 124.5000),
            (19, 'Malaka', -9.5667, 124.9000);

            -- KALIMANTAN BARAT
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (20, 'Pontianak', -0.0263, 109.3425),
            (20, 'Singkawang', 0.9064, 108.9897),
            (20, 'Bengkayang', 0.8667, 109.4667),
            (20, 'Kapuas Hulu', 0.5167, 112.5000),
            (20, 'Kayong Utara', -1.1833, 110.2667),
            (20, 'Ketapang', -1.8500, 110.0000),
            (20, 'Kubu Raya', -0.3500, 109.3667),
            (20, 'Landak', -0.9000, 109.8333),
            (20, 'Melawi', -0.6667, 111.7667),
            (20, 'Mempawah', 0.2833, 109.1833),
            (20, 'Sambas', 1.3667, 109.3000),
            (20, 'Sanggau', 0.1667, 110.3333),
            (20, 'Sekadau', 0.1000, 110.9333),
            (20, 'Sintang', 0.0667, 111.5000);

            -- KALIMANTAN TENGAH
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (21, 'Palangka Raya', -2.2136, 113.9231),
            (21, 'Barito Selatan', -1.5833, 114.7500),
            (21, 'Barito Timur', -1.9167, 115.0000),
            (21, 'Barito Utara', -0.6667, 114.8333),
            (21, 'Gunung Mas', -1.0833, 113.9333),
            (21, 'Kapuas', -2.0167, 114.3833),
            (21, 'Katingan', -1.7333, 113.2667),
            (21, 'Kotawaringin Barat', -2.6833, 111.6167),
            (21, 'Kotawaringin Timur', -2.4000, 112.9333),
            (21, 'Lamandau', -2.5167, 111.3500),
            (21, 'Murung Raya', -0.8667, 114.6667),
            (21, 'Pulang Pisau', -2.7500, 113.9667),
            (21, 'Seruyan', -2.3167, 112.4667),
            (21, 'Sukamara', -2.8667, 111.2500);

            -- KALIMANTAN SELATAN
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (22, 'Banjarmasin', -3.3194, 114.5906),
            (22, 'Banjarbaru', -3.4539, 114.8378),
            (22, 'Balangan', -2.3167, 115.6333),
            (22, 'Banjar', -3.3500, 115.0000),
            (22, 'Barito Kuala', -3.1000, 114.6500),
            (22, 'Hulu Sungai Selatan', -2.7833, 115.2167),
            (22, 'Hulu Sungai Tengah', -2.6000, 115.4000),
            (22, 'Hulu Sungai Utara', -2.4833, 115.1667),
            (22, 'Kotabaru', -3.3000, 116.1667),
            (22, 'Tabalong', -1.9000, 115.4333),
            (22, 'Tanah Bumbu', -3.5167, 115.4667),
            (22, 'Tanah Laut', -3.8167, 114.8667),
            (22, 'Tapin', -2.9167, 115.1500);

            -- KALIMANTAN TIMUR
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (23, 'Samarinda', -0.5022, 117.1536),
            (23, 'Balikpapan', -1.2675, 116.8289),
            (23, 'Bontang', 0.1333, 117.5000),
            (23, 'Berau', 2.1667, 117.4833),
            (23, 'Kutai Barat', 0.5167, 115.8333),
            (23, 'Kutai Kartanegara', -0.4167, 117.2500),
            (23, 'Kutai Timur', 0.5833, 117.6833),
            (23, 'Mahakam Ulu', 0.6167, 115.1333),
            (23, 'Paser', -1.9167, 116.2167),
            (23, 'Penajam Paser Utara', -1.1833, 116.6167);

            -- KALIMANTAN UTARA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (24, 'Tarakan', 3.3000, 117.6333),
            (24, 'Bulungan', 2.9667, 117.0833),
            (24, 'Malinau', 3.5833, 116.5667),
            (24, 'Nunukan', 4.0833, 117.6667),
            (24, 'Tana Tidung', 3.5167, 117.2500);

            -- SULAWESI UTARA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (25, 'Manado', 1.4748, 124.8421),
            (25, 'Bitung', 1.4500, 125.1833),
            (25, 'Kotamobagu', 0.7167, 124.3167),
            (25, 'Tomohon', 1.3333, 124.8333),
            (25, 'Bolaang Mongondow', 0.7167, 124.0000),
            (25, 'Bolaang Mongondow Selatan', 0.3833, 123.8333),
            (25, 'Bolaang Mongondow Timur', 0.7833, 124.4667),
            (25, 'Bolaang Mongondow Utara', 0.8833, 123.6500),
            (25, 'Kepulauan Sangihe', 3.5833, 125.5167),
            (25, 'Kepulauan Siau Tagulandang Biaro', 2.7333, 125.4167),
            (25, 'Kepulauan Talaud', 4.2500, 126.7500),
            (25, 'Minahasa', 1.1667, 124.8333),
            (25, 'Minahasa Selatan', 0.9667, 124.5667),
            (25, 'Minahasa Tenggara', 0.8833, 124.9833),
            (25, 'Minahasa Utara', 1.4833, 125.0333);

            -- GORONTALO
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (26, 'Gorontalo', 0.5435, 123.0585),
            (26, 'Boalemo', 0.5667, 122.3833),
            (26, 'Bone Bolango', 0.5500, 123.2833),
            (26, 'Gorontalo', 0.6333, 122.8333),
            (26, 'Gorontalo Utara', 0.8833, 122.4667),
            (26, 'Pohuwato', 0.7167, 121.6167);

            -- SULAWESI TENGAH
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (27, 'Palu', -0.8917, 119.8706),
            (27, 'Banggai', -1.4333, 123.5000),
            (27, 'Banggai Kepulauan', -1.6333, 123.5333),
            (27, 'Banggai Laut', -1.6000, 123.8500),
            (27, 'Buol', 1.1000, 121.4000),
            (27, 'Donggala', -0.4333, 119.7500),
            (27, 'Morowali', -2.6167, 121.9167),
            (27, 'Morowali Utara', -1.8167, 121.5833),
            (27, 'Parigi Moutong', -0.3833, 120.7500),
            (27, 'Poso', -1.4000, 120.7500),
            (27, 'Sigi', -1.3167, 119.9667),
            (27, 'Tojo Una-Una', -1.4167, 121.7167),
            (27, 'Toli-Toli', 1.0833, 120.8000);

            -- SULAWESI BARAT
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (28, 'Mamuju', -2.6736, 118.8889),
            (28, 'Majene', -3.5333, 118.9833),
            (28, 'Kabupaten Mamuju', -2.5333, 119.3333),
            (28, 'Mamuju Tengah', -2.2833, 119.4167),
            (28, 'Mamuju Utara', -1.5167, 119.3667),
            (28, 'Pasangkayu', -0.8167, 119.3167),
            (28, 'Polewali Mandar', -3.4333, 119.3500);

            -- SULAWESI SELATAN
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (29, 'Makassar', -5.1477, 119.4327),
            (29, 'Palopo', -3.0000, 120.2000),
            (29, 'Parepare', -4.0167, 119.6333),
            (29, 'Bantaeng', -5.5167, 120.0167),
            (29, 'Barru', -4.4167, 119.6667),
            (29, 'Bone', -4.7333, 120.1667),
            (29, 'Bulukumba', -5.5500, 120.2000),
            (29, 'Enrekang', -3.5500, 119.7833),
            (29, 'Gowa', -5.3000, 119.7667),
            (29, 'Jeneponto', -5.6833, 119.6833),
            (29, 'Kepulauan Selayar', -6.1167, 120.4667),
            (29, 'Luwu', -2.8500, 120.6167),
            (29, 'Luwu Timur', -2.5333, 121.0833),
            (29, 'Luwu Utara', -2.2667, 120.2833),
            (29, 'Maros', -4.9833, 119.5833),
            (29, 'Pangkajene dan Kepulauan', -4.8167, 119.5667),
            (29, 'Pinrang', -3.7833, 119.6333),
            (29, 'Sidenreng Rappang', -3.9500, 119.9167),
            (29, 'Sinjai', -5.1333, 120.2500),
            (29, 'Soppeng', -4.3500, 119.8833),
            (29, 'Takalar', -5.4167, 119.4833),
            (29, 'Tana Toraja', -3.0833, 119.8333),
            (29, 'Toraja Utara', -2.8667, 119.8667),
            (29, 'Wajo', -4.0000, 120.0833);

            -- SULAWESI TENGGARA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (30, 'Kendari', -3.9450, 122.5989),
            (30, 'Bau-Bau', -5.4714, 122.6297),
            (30, 'Bombana', -4.7333, 121.8833),
            (30, 'Buton', -5.3667, 122.9667),
            (30, 'Buton Selatan', -5.8333, 122.8667),
            (30, 'Buton Tengah', -5.1167, 122.8167),
            (30, 'Buton Utara', -4.7667, 123.0500),
            (30, 'Kolaka', -4.0500, 121.6000),
            (30, 'Kolaka Timur', -3.8000, 121.5833),
            (30, 'Kolaka Utara', -3.2500, 121.1167),
            (30, 'Konawe', -3.9000, 122.1667),
            (30, 'Konawe Kepulauan', -4.1667, 123.6667),
            (30, 'Konawe Selatan', -4.2500, 122.5333),
            (30, 'Konawe Utara', -3.4667, 121.9167),
            (30, 'Muna', -4.9000, 122.6167),
            (30, 'Muna Barat', -4.8500, 122.3167),
            (30, 'Wakatobi', -5.4833, 123.6000);

            -- MALUKU
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (31, 'Ambon', -3.6954, 128.1814),
            (31, 'Tual', -5.6289, 132.7522),
            (31, 'Buru', -3.3333, 126.6667),
            (31, 'Buru Selatan', -3.6667, 126.5833),
            (31, 'Kepulauan Aru', -6.1833, 134.5333),
            (31, 'Maluku Barat Daya', -7.9167, 126.3333),
            (31, 'Maluku Tengah', -3.2167, 129.4833),
            (31, 'Maluku Tenggara', -5.7500, 132.7333),
            (31, 'Maluku Tenggara Barat', -7.5833, 131.2833),
            (31, 'Seram Bagian Barat', -3.1667, 128.3333),
            (31, 'Seram Bagian Timur', -3.4000, 130.1667);

            -- MALUKU UTARA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (32, 'Ternate', 0.7833, 127.3667),
            (32, 'Tidore Kepulauan', 0.6833, 127.5500),
            (32, 'Halmahera Barat', 1.4167, 127.5500),
            (32, 'Halmahera Selatan', -1.3000, 127.8833),
            (32, 'Halmahera Tengah', 0.6333, 128.0667),
            (32, 'Halmahera Timur', 1.0667, 128.4667),
            (32, 'Halmahera Utara', 1.8333, 127.8833),
            (32, 'Kepulauan Sula', -1.8833, 125.9500),
            (32, 'Pulau Morotai', 2.3833, 128.4000),
            (32, 'Pulau Taliabu', -1.8333, 124.7833);

            -- PAPUA BARAT
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (33, 'Sorong', -0.8667, 131.2500),
            (33, 'Fakfak', -2.9167, 132.3000),
            (33, 'Kaimana', -3.6500, 133.6833),
            (33, 'Manokwari', -0.8667, 134.0833),
            (33, 'Manokwari Selatan', -1.4167, 134.0500),
            (33, 'Maybrat', -1.3000, 132.3333),
            (33, 'Pegunungan Arfak', -1.2667, 133.8667),
            (33, 'Raja Ampat', -0.5000, 130.8333),
            (33, 'Sorong', -1.0833, 131.7500),
            (33, 'Sorong Selatan', -1.8500, 132.1167),
            (33, 'Tambrauw', -0.6167, 132.6667),
            (33, 'Teluk Bintuni', -2.0833, 133.5333),
            (33, 'Teluk Wondama', -2.7500, 134.3833);

            -- PAPUA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (34, 'Jayapura', -2.5333, 140.7167),
            (34, 'Asmat', -5.0500, 138.4667),
            (34, 'Biak Numfor', -1.0500, 136.0833),
            (34, 'Boven Digoel', -5.8167, 140.3667),
            (34, 'Deiyai', -4.1667, 136.3333),
            (34, 'Dogiyai', -4.0000, 135.5000),
            (34, 'Intan Jaya', -3.5667, 136.7667),
            (34, 'Jayapura', -2.6667, 140.5000),
            (34, 'Jayawijaya', -3.9667, 138.9167),
            (34, 'Keerom', -3.2833, 140.5833),
            (34, 'Kepulauan Yapen', -1.8167, 136.2000),
            (34, 'Lanny Jaya', -3.9000, 138.3000),
            (34, 'Mamberamo Raya', -2.3500, 138.3333),
            (34, 'Mamberamo Tengah', -2.6500, 138.0500),
            (34, 'Mappi', -6.5833, 139.3500),
            (34, 'Merauke', -8.4667, 140.4000),
            (34, 'Mimika', -4.5333, 136.5500),
            (34, 'Nabire', -3.3667, 135.4833),
            (34, 'Nduga', -4.3667, 137.6500),
            (34, 'Paniai', -3.8833, 136.3500),
            (34, 'Pegunungan Bintang', -4.6167, 140.5167),
            (34, 'Puncak', -3.6667, 137.2667),
            (34, 'Puncak Jaya', -3.4500, 137.2000),
            (34, 'Sarmi', -1.8833, 138.7833),
            (34, 'Supiori', -0.7833, 135.6333),
            (34, 'Tolikara', -3.4833, 138.3833),
            (34, 'Waropen', -2.1667, 136.8167),
            (34, 'Yahukimo', -4.7000, 139.5000),
            (34, 'Yalimo', -3.7500, 139.0167);

            -- PAPUA TENGAH
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (35, 'Nabire', -3.3667, 135.4833),
            (35, 'Deiyai', -4.1667, 136.3333),
            (35, 'Dogiyai', -4.0000, 135.5000),
            (35, 'Intan Jaya', -3.5667, 136.7667),
            (35, 'Mimika', -4.5333, 136.5500),
            (35, 'Paniai', -3.8833, 136.3500),
            (35, 'Puncak', -3.6667, 137.2667),
            (35, 'Puncak Jaya', -3.4500, 137.2000);

            -- PAPUA PEGUNUNGAN
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (36, 'Jayawijaya', -3.9667, 138.9167),
            (36, 'Lanny Jaya', -3.9000, 138.3000),
            (36, 'Mamberamo Tengah', -2.6500, 138.0500),
            (36, 'Nduga', -4.3667, 137.6500),
            (36, 'Tolikara', -3.4833, 138.3833),
            (36, 'Yalimo', -3.7500, 139.0167),
            (36, 'Yahukimo', -4.7000, 139.5000),
            (36, 'Pegunungan Bintang', -4.6167, 140.5167);

            -- PAPUA SELATAN
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (37, 'Merauke', -8.4667, 140.4000),
            (37, 'Asmat', -5.0500, 138.4667),
            (37, 'Boven Digoel', -5.8167, 140.3667),
            (37, 'Mappi', -6.5833, 139.3500);

            -- PAPUA BARAT DAYA
            INSERT INTO regencies (province_id, name, latitude, longitude) VALUES
            (38, 'Sorong', -0.8667, 131.2500),
            (38, 'Fakfak', -2.9167, 132.3000),
            (38, 'Kaimana', -3.6500, 133.6833),
            (38, 'Maybrat', -1.3000, 132.3333),
            (38, 'Raja Ampat', -0.5000, 130.8333),
            (38, 'Sorong', -1.0833, 131.7500),
            (38, 'Sorong Selatan', -1.8500, 132.1167),
            (38, 'Tambrauw', -0.6167, 132.6667),
            (38, 'Teluk Bintuni', -2.0833, 133.5333),
            (38, 'Teluk Wondama', -2.7500, 134.3833);

            -- Buat index untuk pencarian yang lebih cepat
            CREATE INDEX idx_provinces_name ON provinces(name);
            CREATE INDEX idx_regencies_name ON regencies(name);
            CREATE INDEX idx_regencies_provinces ON regencies(province_id);";
        DB::unprepared($query);
    }
}
