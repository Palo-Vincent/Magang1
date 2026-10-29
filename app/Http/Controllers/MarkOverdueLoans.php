<?php

namespace App\Console\Commands;

use App\Models\Loan;
use Illuminate\Console\Command;

class MarkOverdueLoans extends Command
{
    protected $signature = 'loans:mark-overdue';

    protected $description = 'Ubah status peminjaman yang melewati batas pengembalian menjadi terlambat';

    public function handle(): int
    {
        $count = Loan::markOverdue();

        $this->info("{$count} peminjaman ditandai terlambat.");

        return self::SUCCESS;
    }
}