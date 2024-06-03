<?php

namespace Database\Seeders;

use App\Models\Diskon;
use App\Models\JenisObat;
use App\Models\Obat;
use App\Models\Satuan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* ============================
            Seeder untuk User
        ===============================*/
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'address' => 'Jl. Admin',
                'phone' => '081234567890',
                'password' => Hash::make('admin'),
                'roles' => 'Admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'address' => 'Jl. User',
                'phone' => '081234567891',
                'password' => Hash::make('user'),
                'roles' => 'User',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Generate additional 18 users using the factory
        User::factory()->count(18)->create();

        $nama_satuan = [
            'Botol',
            'Strip',
            'Tablet',
        ];

        foreach ($nama_satuan as $satuan) {
            Satuan::create([
                'nama_satuan' => $satuan,
            ]);
        }

        /* ============================
            Seeder untuk jenis obat
        ===============================*/
        $nama_jenis_obat = [
            'Obat Batuk',
            'Obat Flu',
            'Obat Pusing',
            'Obat Sakit Gigi',
            'Obat Luka',
            'Obat Asma',
            'Obat Alergi',
        ];

        foreach ($nama_jenis_obat as $jenis_obat) {
            JenisObat::create([
                'nama_jenis_obat' => $jenis_obat,
            ]);
        }

        /* ============================
            Seeder untuk diskon
        ===============================*/

        $persentase_diskon = [
            0,
            10,
            20,
            30,
        ];

        $nama_diskon = [
            'Akhir Tahun',
            'Hari Belanja Diskon',
            'Hari Belanja Diskon Nasional',
            'Hari Belanja Diskon Internasional',
        ];

        foreach ($persentase_diskon as $key => $diskon) {
            Diskon::create([
                'persentase_diskon' => $diskon,
                'nama_diskon' => $nama_diskon[$key],
            ]);
        }

        /* ============================
            Seeder untuk nama obat
        ===============================*/

        $nama_obat = [
            'Paracetamol',
            'Amoxilin',
            'CTM',
            'OBH',
            'Antalgin',
            'Betadine',
            'Bodrex',
            'Promag',
            'Antimo',
            'Antangin',
            'Antasida',
            'Antalgina',
            'Xanax',
            'Dexamethasone',
            'Cetirizine',
            'Dextaco',
            'Dextafen',
            'Dextamine',
            'Dextamol',
            'Dextan',
        ];

        foreach ($nama_obat as $obat) {
            Obat::create([
                'nama_obat' => $obat,
                'harga' => rand(5000, 200000),
                'stok' => rand(1, 100),
                'jenis_obat_id' => rand(1, 7),
                'diskon_id' => rand(1, 4),
                'satuan_id' => rand(1, 3),
            ]);
        }
    }
}
