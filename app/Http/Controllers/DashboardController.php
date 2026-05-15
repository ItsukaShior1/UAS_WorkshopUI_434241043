<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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
        
        $posts = [
            ['id' => 1, 'author' => 'Admin Bookify', 'category' => 'Kisah Sukses', 'title' => 'Dari Kecil Menjadi Besar', 'content' => 'Perjalanan saya membangun usaha dari modal kecil hingga sekarang bisa omset jutaan per bulan.', 'likes' => 245, 'comments' => 32],
            ['id' => 2, 'author' => 'Toko Kelontong', 'category' => 'Tips & Trik', 'title' => 'Cara Kelola Stok Efisien', 'content' => 'Berbagi tips cara saya mengelola stok tanpa kelebihan atau kekurangan', 'likes' => 156, 'comments' => 18],
            ['id' => 3, 'author' => 'Freelancer Profesional', 'category' => 'Tantangan', 'title' => 'Menghadapi Persaingan Pasar', 'content' => 'Bagaimana kita bisa bertahan dan berkembang di tengah persaingan yang ketat?', 'likes' => 89, 'comments' => 27],
        ];
        
        return view('dashboard.community', compact('user', 'posts'));
    }
}
