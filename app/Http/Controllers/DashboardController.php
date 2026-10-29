<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        Loan::markOverdue();

        $user = $request->user();

        if ($user->isAdmin()) {
            return view('dashboard', [
                'isAdmin' => true,
                'stats' => [
                    'Jenis barang' => Item::count(),
                    'Total stok tersedia' => (int) Item::sum('stock'),
                    'Menunggu verifikasi' => Loan::where('status', 'menunggu')->count(),
                    'Sedang dipinjam' => Loan::where('status', 'dipinjam')->count(),
                    'Terlambat' => Loan::where('status', 'terlambat')->count(),
                    'Pengguna' => User::where('role', 'user')->count(),
                ],
                'recentLoans' => Loan::with(['user', 'item'])->latest('id')->take(5)->get(),
                'popularItems' => Item::withCount('loans')->orderByDesc('loans_count')->take(5)->get(),
            ]);
        }

        return view('dashboard', [
            'isAdmin' => false,
            'stats' => [
                'Total peminjaman' => $user->loans()->count(),
                'Sedang dipinjam' => $user->loans()->where('status', 'dipinjam')->count(),
                'Terlambat' => $user->loans()->where('status', 'terlambat')->count(),
                'Sudah dikembalikan' => $user->loans()->where('status', 'dikembalikan')->count(),
            ],
            'recentLoans' => $user->loans()->with('item')->latest('id')->take(5)->get(),
            'popularItems' => collect(),
        ]);
    }
}