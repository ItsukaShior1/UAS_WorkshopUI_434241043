<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function communityPosts(): array
    {
        return [
            ['id' => 1, 'author' => 'Admin Bookify', 'avatar' => 'https://i.pinimg.com/736x/c7/95/cf/c795cf18419aea9ca42302ad9af149f6.jpg', 'cover' => 'https://i.pinimg.com/736x/c7/95/cf/c795cf18419aea9ca42302ad9af149f6.jpg', 'category' => 'Kisah Sukses', 'title' => 'Dari Kecil Menjadi Besar', 'excerpt' => 'Perjalanan saya membangun usaha dari modal kecil hingga sekarang bisa omset jutaan per bulan.', 'content' => 'Perjalanan saya membangun usaha dari modal kecil hingga sekarang bisa omset jutaan per bulan. Awalnya saya hanya berjualan dari rumah dengan stok terbatas. Setelah konsisten mencatat transaksi dan memahami kebutuhan pelanggan, usaha ini mulai berkembang. Kuncinya ada di disiplin, pelayanan, dan keberanian mencoba promosi baru.', 'date' => '15 Juli 2023', 'read_time' => '5 menit baca', 'likes' => 245, 'comments' => 32, 'views' => '1.2k', 'author_role' => 'UMKM Mikro'],
            ['id' => 2, 'author' => 'Toko Kelontong', 'avatar' => 'https://i.pinimg.com/originals/6a/99/0c/6a990c97109eef77fa91c07df634be60.jpg', 'cover' => 'https://i.pinimg.com/originals/6a/99/0c/6a990c97109eef77fa91c07df634be60.jpg', 'category' => 'Tips & Trik', 'title' => 'Cara Kelola Stok Efisien', 'excerpt' => 'Berbagi tips cara saya mengelola stok tanpa kelebihan atau kekurangan.', 'content' => 'Berbagi tips cara saya mengelola stok tanpa kelebihan atau kekurangan. Saya mulai dengan membuat daftar barang fast moving dan slow moving, lalu mengevaluasi stok setiap minggu. Dengan pencatatan yang rapi, barang tidak menumpuk dan modal bisa diputar lebih cepat. Sistem sederhana ini sangat membantu menjaga arus kas tetap sehat.', 'date' => '2 Agustus 2023', 'read_time' => '4 menit baca', 'likes' => 156, 'comments' => 18, 'views' => '862', 'author_role' => 'Pemilik Toko'],
            ['id' => 3, 'author' => 'Freelancer Profesional', 'avatar' => 'https://i.kym-cdn.com/entries/icons/facebook/000/052/237/cover3.jpg', 'cover' => 'https://i.kym-cdn.com/entries/icons/facebook/000/052/237/cover3.jpg', 'category' => 'Tantangan', 'title' => 'Menghadapi Persaingan Pasar', 'excerpt' => 'Bagaimana kita bisa bertahan dan berkembang di tengah persaingan yang ketat?', 'content' => 'Bagaimana kita bisa bertahan dan berkembang di tengah persaingan yang ketat? Saya belajar bahwa diferensiasi layanan, komunikasi yang cepat, dan portofolio yang jelas sangat menentukan. Pasar memang padat, tetapi masih banyak ruang untuk tumbuh jika kita konsisten memperbaiki kualitas kerja dan menjaga kepercayaan klien.', 'date' => '21 September 2023', 'read_time' => '6 menit baca', 'likes' => 89, 'comments' => 27, 'views' => '540', 'author_role' => 'Independent Freelancer'],
        ];
    }

    private function findCommunityPost(int $postId): array
    {
        $posts = collect($this->communityPosts());
        $post = $posts->firstWhere('id', $postId);

        abort_if(! $post, 404);

        return $post;
    }

    /**
     * Show dashboard home
     */
    public function home()
    {
        $user = Auth::user();
        
        // Dummy data untuk dashboard
        $balance = 1550000;
        $income = 2250000;
        $expense = 700000;
        $trend = 15;
        
        $transactions = [
            ['type' => 'income', 'name' => 'Penjualan Produk A', 'amount' => 500000, 'date' => '2024-05-10', 'category' => 'Penjualan'],
            ['type' => 'expense', 'name' => 'Beli Bahan Baku', 'amount' => 300000, 'date' => '2024-05-09', 'category' => 'Bahan Baku'],
            ['type' => 'income', 'name' => 'Penjualan Online', 'amount' => 750000, 'date' => '2024-05-08', 'category' => 'Penjualan'],
            ['type' => 'expense', 'name' => 'Gaji Karyawan', 'amount' => 400000, 'date' => '2024-05-07', 'category' => 'Gaji'],
        ];
        
        return view('dashboard.home', compact('user', 'balance', 'income', 'expense', 'trend', 'transactions'));
    }

    /**
     * Show transactions page
     */
    public function transactions()
    {
        $user = Auth::user();
        
        $transactions = [
            ['id' => 1, 'type' => 'income', 'name' => 'Penjualan Produk A', 'amount' => 500000, 'date' => '2024-05-10', 'category' => 'Penjualan', 'status' => 'success'],
            ['id' => 2, 'type' => 'expense', 'name' => 'Beli Bahan Baku', 'amount' => 300000, 'date' => '2024-05-09', 'category' => 'Bahan Baku', 'status' => 'success'],
            ['id' => 3, 'type' => 'income', 'name' => 'Penjualan Online', 'amount' => 750000, 'date' => '2024-05-08', 'category' => 'Penjualan', 'status' => 'success'],
            ['id' => 4, 'type' => 'expense', 'name' => 'Gaji Karyawan', 'amount' => 400000, 'date' => '2024-05-07', 'category' => 'Gaji', 'status' => 'success'],
            ['id' => 5, 'type' => 'income', 'name' => 'Bonus Penjualan', 'amount' => 200000, 'date' => '2024-05-06', 'category' => 'Penjualan', 'status' => 'success'],
        ];
        
        return view('dashboard.transactions', compact('user', 'transactions'));
    }

    /**
     * Show stock page
     */
    public function stock()
    {
        $user = Auth::user();
        
        $products = [
            ['id' => 1, 'name' => 'Produk A', 'stock' => 15, 'min' => 10, 'price' => 50000, 'status' => 'normal'],
            ['id' => 2, 'name' => 'Produk B', 'stock' => 5, 'min' => 10, 'price' => 75000, 'status' => 'warning'],
            ['id' => 3, 'name' => 'Produk C', 'stock' => 20, 'min' => 10, 'price' => 100000, 'status' => 'normal'],
            ['id' => 4, 'name' => 'Bahan Baku X', 'stock' => 2, 'min' => 5, 'price' => 25000, 'status' => 'critical'],
        ];
        
        return view('dashboard.stock', compact('user', 'products'));
    }

    /**
     * Show reports page
     */
    public function reports()
    {
        $user = Auth::user();
        
        $summary = [
            'month' => 'Mei 2024',
            'income' => 2250000,
            'expense' => 700000,
            'balance' => 1550000,
        ];
        
        // Data untuk bar chart (Pemasukan vs Pengeluaran)
        $chartData = [
            ['month' => 'Januari', 'income' => 1500000, 'expense' => 800000],
            ['month' => 'Februari', 'income' => 1800000, 'expense' => 900000],
            ['month' => 'Maret', 'income' => 2000000, 'expense' => 850000],
            ['month' => 'April', 'income' => 1900000, 'expense' => 750000],
            ['month' => 'Mei', 'income' => 2250000, 'expense' => 700000],
        ];
        
        // Data untuk pie chart (Distribusi Pengeluaran)
        $expenseDistribution = [
            ['name' => 'Bahan Baku', 'percentage' => 40, 'amount' => 280000],
            ['name' => 'Gaji', 'percentage' => 25, 'amount' => 175000],
            ['name' => 'Utilitas', 'percentage' => 10, 'amount' => 70000],
            ['name' => 'Lainnya', 'percentage' => 25, 'amount' => 175000],
        ];
        
        return view('dashboard.reports', compact('user', 'summary', 'chartData', 'expenseDistribution'));
    }

    /**
     * Show insights page
     */
    public function insights()
    {
        $user = Auth::user();
        
        $trends = [
            ['title' => 'Penjualan Online Meningkat', 'description' => 'Penjualan melalui channel online naik 45% dibanding bulan lalu', 'icon' => '📈'],
            ['title' => 'Stok Efisien', 'description' => 'Tingkat perputaran stok meningkat 30% dengan pengelolaan yang lebih baik', 'icon' => '📦'],
            ['title' => 'Margin Keuntungan Optimal', 'description' => 'Margin keuntungan mencapai 35% - lebih tinggi dari rata-rata UMKM', 'icon' => '💰'],
        ];
        
        return view('dashboard.insights', compact('user', 'trends'));
    }

    /**
     * Show community page
     */
    public function community()
    {
        $user = Auth::user();

        $posts = $this->communityPosts();

        return view('dashboard.community', compact('user', 'posts'));
    }

    public function communityCreate()
    {
        $user = Auth::user();

        $categories = ['Kisah Sukses', 'Tips & Trik', 'Tantangan', 'Lainnya'];

        return view('dashboard.community-create', compact('user', 'categories'));
    }

    public function communityShow(int $post)
    {
        $user = Auth::user();
        $post = $this->findCommunityPost($post);

        $comments = [
            ['author' => 'Ahmad Rizki', 'time' => '2 hari lalu', 'text' => 'Terima kasih sudah berbagi! Sangat inspiratif dan membantu saya yang baru memulai usaha.'],
            ['author' => 'Linda Susanti', 'time' => '1 hari lalu', 'text' => 'Saya juga mengalami hal serupa. Tips yang sangat berguna!'],
        ];

        return view('dashboard.community-detail', compact('user', 'post', 'comments'));
    }
}
