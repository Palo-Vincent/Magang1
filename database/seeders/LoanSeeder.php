<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $budi = User::where('email', 'budi@example.com')->firstOrFail();
        $siti = User::where('email', 'siti@example.com')->firstOrFail();

        $loans = [
            [$budi, 'LAB-001', 1, now()->subDays(20), now()->subDays(15), 'dikembalikan', null],
            [$siti, 'OLR-001', 2, now()->subDays(12), now()->subDays(9), 'dikembalikan', null],
            [$budi, 'ELK-001', 1, now()->subDays(8), now()->subDays(6), 'dikembalikan', null],
            [$siti, 'ELK-002', 1, now()->subDays(2), now()->addDays(3), 'dipinjam', null],
            [$budi, 'OLR-002', 2, now()->subDays(10), now()->subDays(4), 'terlambat', null],
            [$siti, 'ELK-001', 1, now(), now()->addDays(5), 'menunggu', null],
            [$budi, 'LAB-001', 1, now()->subDays(3), now()->addDays(2), 'ditolak', 'Barang sedang dipakai praktikum'],
        ];

        foreach ($loans as [$user, $code, $quantity, $loanDate, $dueDate, $status, $note]) {
            $item = Item::where('code', $code)->firstOrFail();
            $verified = $status !== 'menunggu';

            Loan::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'quantity' => $quantity,
                'loan_date' => $loanDate->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'status' => $status,
                'verified_by' => $verified ? $admin->id : null,
                'verified_at' => $verified ? $loanDate : null,
                'rejection_note' => $note,
            ]);
        }
    }
}