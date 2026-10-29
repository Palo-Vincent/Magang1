<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\ReturnLog;
use Illuminate\Database\Seeder;

class ReturnLogSeeder extends Seeder
{
    public function run(): void
    {
        $loans = Loan::where('status', 'dikembalikan')->orderBy('id')->get();
        $conditions = ['baik', 'baik', 'rusak ringan'];

        foreach ($loans as $index => $loan) {
            ReturnLog::create([
                'loan_id' => $loan->id,
                'returned_at' => $loan->due_date->copy()->subDay()->toDateString(),
                'condition_on_return' => $conditions[$index] ?? 'baik',
                'notes' => $index === 2 ? 'Ada lecet pada bodi' : null,
            ]);
        }
    }
}