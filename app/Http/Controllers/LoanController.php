<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use App\Http\Requests\RejectLoanRequest;
use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\Loan;
use App\Support\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        Loan::markOverdue();

        $user = $request->user();
        $isAdmin = $user->isAdmin();

        $loans = Loan::with(['user', 'item', 'returnLog'])
            ->when(! $isAdmin, fn ($query) => $query->where('user_id', $user->id))
            ->when($request->status, fn ($query, $value) => $query->where('status', $value))
            ->when($request->q, fn ($query, $search) => $query->where(function ($w) use ($search, $isAdmin) {
                $w->whereHas('item', fn ($i) => $i->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"));

                if ($isAdmin) {
                    $w->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                }
            }))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('loans.index', compact('loans', 'isAdmin'));
    }

    public function create()
    {
        $items = Item::available()->with('category')->orderBy('name')->get();

        return view('loans.create', compact('items'));
    }

    public function store(LoanRequest $request)
    {
        $data = $request->validated();
        $userId = $request->user()->id;
        $item = Item::findOrFail($data['item_id']);

        if ($item->condition === 'rusak berat') {
            throw ValidationException::withMessages([
                'item_id' => 'Barang ini rusak berat dan tidak dapat dipinjam.',
            ]);
        }

        if ($data['quantity'] > $item->stock) {
            throw ValidationException::withMessages([
                'quantity' => 'Jumlah melebihi stok tersedia ('.Format::number($item->stock).').',
            ]);
        }

        // Stok belum dikurangi di sini; baru berkurang saat Admin menyetujui.
        Loan::create([
            'user_id' => $userId,
            'item_id' => $item->id,
            'quantity' => $data['quantity'],
            'loan_date' => $data['loan_date'],
            'due_date' => $data['due_date'],
            'status' => 'menunggu',
        ]);

        ActivityLog::record(
            'create_loan',
            "Mengajukan peminjaman {$item->name} sebanyak ".Format::number($data['quantity']).' unit (menunggu verifikasi)',
            $userId
        );

        return redirect()->route('loans.index')
            ->with('success', 'Pengajuan terkirim dan menunggu verifikasi Admin.');
    }

    public function approve(Request $request, Loan $loan)
    {
        $adminId = $request->user()->id;

        $error = DB::transaction(function () use ($loan, $adminId) {
            $loan = Loan::with(['user', 'item'])->lockForUpdate()->findOrFail($loan->id);

            if ($loan->status !== 'menunggu') {
                return 'Pengajuan ini sudah diproses.';
            }

            if ($loan->due_date->lt(today())) {
                return 'Batas pengembalian sudah lewat. Silakan tolak pengajuan ini.';
            }

            $item = Item::lockForUpdate()->findOrFail($loan->item_id);

            if ($item->condition === 'rusak berat') {
                return 'Barang ini rusak berat dan tidak dapat dipinjamkan.';
            }

            if ($loan->quantity > $item->stock) {
                return 'Stok tidak mencukupi (tersedia '.Format::number($item->stock).').';
            }

            $item->decrement('stock', $loan->quantity);

            $loan->update([
                'status' => 'dipinjam',
                'verified_by' => $adminId,
                'verified_at' => now(),
            ]);

            ActivityLog::record(
                'verify_loan',
                "Menyetujui peminjaman {$item->name} oleh {$loan->user->name} sebanyak "
                .Format::number($loan->quantity).' unit',
                $adminId
            );

            return null;
        });

        return back()->with($error ? 'error' : 'success', $error ?? 'Peminjaman disetujui.');
    }

    public function reject(RejectLoanRequest $request, Loan $loan)
    {
        if ($loan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $loan->load(['user', 'item']);

        $loan->update([
            'status' => 'ditolak',
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
            'rejection_note' => $request->validated('rejection_note'),
        ]);

        ActivityLog::record('reject_loan', "Menolak peminjaman {$loan->item->name} oleh {$loan->user->name}");

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function destroy(Request $request, Loan $loan)
    {
        abort_unless($loan->user_id === $request->user()->id && $loan->status === 'menunggu', 403);

        $loan->delete();

        ActivityLog::record('cancel_loan', "Membatalkan pengajuan peminjaman {$loan->item->name}");

        return redirect()->route('loans.index')->with('success', 'Pengajuan dibatalkan.');
    }
}