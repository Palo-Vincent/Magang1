<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnLogRequest;
use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\Loan;
use App\Models\ReturnLog;
use App\Support\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturnLogController extends Controller
{
    public function index(Request $request)
    {
        $returnLogs = ReturnLog::with(['loan.user', 'loan.item'])
            ->when($request->condition, fn ($query, $value) => $query->where('condition_on_return', $value))
            ->when($request->q, fn ($query, $search) => $query->whereHas('loan', function ($l) use ($search) {
                $l->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('item', fn ($i) => $i->where('name', 'like', "%{$search}%"));
            }))
            ->latest('returned_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('returns.index', compact('returnLogs'));
    }

    public function create(Loan $loan)
    {
        if (! in_array($loan->status, ['dipinjam', 'terlambat'])) {
            return redirect()->route('loans.index')
                ->with('error', 'Peminjaman ini tidak dapat diproses pengembaliannya.');
        }

        $loan->load(['user', 'item']);

        return view('returns.create', compact('loan'));
    }

    public function store(ReturnLogRequest $request, Loan $loan)
    {
        $data = $request->validated();
        $adminId = $request->user()->id;

        DB::transaction(function () use ($data, $loan, $adminId) {
            $loan = Loan::with(['user', 'item'])->lockForUpdate()->findOrFail($loan->id);

            if (! in_array($loan->status, ['dipinjam', 'terlambat']) || $loan->returnLog()->exists()) {
                throw ValidationException::withMessages([
                    'returned_at' => 'Peminjaman ini tidak dapat diproses pengembaliannya.',
                ]);
            }

            ReturnLog::create(array_merge($data, ['loan_id' => $loan->id]));

            $loan->update(['status' => 'dikembalikan']);

            $item = Item::withTrashed()->lockForUpdate()->findOrFail($loan->item_id);
            $condition = $data['condition_on_return'];

            // Rusak berat: unit tidak dikembalikan ke stok.
            if ($condition !== 'rusak berat') {
                $item->stock += $loan->quantity;
            }

            // Rusak ringan: kondisi barang ikut diturunkan jika sebelumnya baik.
            if ($condition === 'rusak ringan' && $item->condition === 'baik') {
                $item->condition = 'rusak ringan';
            }

            $item->save();

            ActivityLog::record(
                'verify_return',
                "Memverifikasi pengembalian {$loan->item->name} ({$loan->user->name}) sebanyak "
                .Format::number($loan->quantity).' unit, kondisi '.$condition
                .($condition === 'rusak berat' ? ' (tidak dikembalikan ke stok)' : ''),
                $adminId
            );
        });

        return redirect()->route('returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}