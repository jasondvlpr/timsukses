<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuickReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\QuickReply::insert([
            [
                'title' => 'Web Disetujui',
                'content' => 'Halo, pengajuan website Anda telah kami tinjau dan dinyatakan memenuhi syarat. Website Anda kini sudah aktif di sistem. Selamat bekerja!',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'title' => 'Link Mati/Error',
                'content' => 'Halo, kami mencoba mengakses link yang Anda berikan namun terjadi error atau link tidak dapat dibuka. Mohon periksa kembali URL Anda dan ajukan ulang.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'title' => 'Butuh Perbaikan Gambar',
                'content' => 'Halo, konten website Anda sudah bagus, namun kualitas gambar/logo masih kurang jelas. Mohon perbarui aset visual Anda agar lebih menarik pengunjung.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'title' => 'Pelanggaran Aturan',
                'content' => 'Halo, pengajuan Anda kami tolak karena terindikasi melanggar aturan komunitas kami. Mohon baca kembali syarat dan ketentuan penggunaan layanan.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'title' => 'Sedang Diproses',
                'content' => 'Halo, laporan Anda sudah kami terima dan saat ini sedang dalam antrean verifikasi tim teknis kami. Mohon kesabarannya menunggu.',
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}
