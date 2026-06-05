<?php

namespace Database\Seeders;

use App\Models\CommunityPost;
use Illuminate\Database\Seeder;

class CommunityPostSeeder extends Seeder
{
    public function run(): void
    {
        if (CommunityPost::query()->exists()) {
            return;
        }

        $seeds = [
            [
                'author_name' => 'Admin Bookify',
                'author_role' => 'UMKM Mikro',
                'avatar_url' => 'https://i.pinimg.com/736x/c7/95/cf/c795cf18419aea9ca42302ad9af149f6.jpg',
                'cover_url' => 'https://i.pinimg.com/736x/c7/95/cf/c795cf18419aea9ca42302ad9af149f6.jpg',
                'category' => 'Kisah Sukses',
                'title' => 'Dari Kecil Menjadi Besar',
                'excerpt' => 'Perjalanan saya membangun usaha dari modal kecil hingga sekarang bisa omset jutaan per bulan.',
                'content' => "Perjalanan saya membangun usaha dari modal kecil hingga sekarang bisa omset jutaan per bulan. \n\nAwalnya saya hanya berjualan dari rumah dengan stok terbatas. Setelah konsisten mencatat transaksi dan memahami kebutuhan pelanggan, usaha ini mulai berkembang. Kuncinya ada di disiplin, pelayanan, dan keberanian mencoba promosi baru.",
            ],
            [
                'author_name' => 'Toko Kelontong',
                'author_role' => 'Pemilik Toko',
                'avatar_url' => 'https://i.pinimg.com/originals/6a/99/0c/6a990c97109eef77fa91c07df634be60.jpg',
                'cover_url' => 'https://i.pinimg.com/originals/6a/99/0c/6a990c97109eef77fa91c07df634be60.jpg',
                'category' => 'Tips & Trik',
                'title' => 'Cara Kelola Stok Efisien',
                'excerpt' => 'Berbagi tips cara saya mengelola stok tanpa kelebihan atau kekurangan.',
                'content' => "Berbagi tips cara saya mengelola stok tanpa kelebihan atau kekurangan. \n\nSaya mulai dengan membuat daftar barang fast moving dan slow moving, lalu mengevaluasi stok setiap minggu. Dengan pencatatan yang rapi, barang tidak menumpuk dan modal bisa diputar lebih cepat. Sistem sederhana ini sangat membantu menjaga arus kas tetap sehat.",
            ],
            [
                'author_name' => 'Freelancer Profesional',
                'author_role' => 'Independent Freelancer',
                'avatar_url' => 'https://i.kym-cdn.com/entries/icons/facebook/000/052/237/cover3.jpg',
                'cover_url' => 'https://i.kym-cdn.com/entries/icons/facebook/000/052/237/cover3.jpg',
                'category' => 'Tantangan',
                'title' => 'Menghadapi Persaingan Pasar',
                'excerpt' => 'Bagaimana kita bisa bertahan dan berkembang di tengah persaingan yang ketat?',
                'content' => "Bagaimana kita bisa bertahan dan berkembang di tengah persaingan yang ketat? \n\nSaya belajar bahwa diferensiasi layanan, komunikasi yang cepat, dan portofolio yang jelas sangat menentukan. Pasar memang padat, tetapi masih banyak ruang untuk tumbuh jika kita konsisten memperbaiki kualitas kerja dan menjaga kepercayaan klien.",
            ],
        ];

        foreach ($seeds as $seed) {
            $seed['likes_count'] = 0;
            $seed['comments_count'] = 0;
            CommunityPost::create($seed);
        }
    }
}
