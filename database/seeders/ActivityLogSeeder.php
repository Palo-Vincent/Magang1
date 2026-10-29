<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $budi = User::where('email', 'budi@example.com')->firstOrFail();
        $siti = User::where('email', 'siti@example.com')->firstOrFail();

        $logs = [
            [$admin, 'login', 'Admin masuk ke sistem'],
            [$admin, 'create_category', 'Menambah kategori Alat Lab'],
            [$admin, 'create_item', 'Menambah barang Mikroskop (LAB-001)'],
            [$budi, 'create_loan', 'Mengajukan peminjaman Mikroskop sebanyak 1 unit (menunggu verifikasi)'],
            [$admin, 'verify_loan', 'Menyetujui peminjaman Mikroskop oleh Budi Santoso sebanyak 1 unit'],
            [$admin, 'verify_return', 'Memverifikasi pengembalian Mikroskop (Budi Santoso) sebanyak 1 unit, kondisi baik'],
            [$admin, 'reject_loan', 'Menolak peminjaman Mikroskop oleh Budi Santoso'],
            [$siti, 'create_loan', 'Mengajukan peminjaman Proyektor sebanyak 1 unit (menunggu verifikasi)'],
            [$admin, 'update_item', 'Mengubah barang Pembakar Bunsen (LAB-004)'],
        ];

        foreach ($logs as [$user, $action, $description]) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'description' => $description,
            ]);
        }
    }
}