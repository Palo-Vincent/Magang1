<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with('category')
            ->when($request->q, fn ($query, $search) => $query->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
            }))
            ->when($request->category_id, fn ($query, $value) => $query->where('category_id', $value))
            ->when($request->condition, fn ($query, $value) => $query->where('condition', $value))
            ->when($request->availability === 'tersedia', fn ($query) => $query->where('stock', '>', 0))
            ->when($request->availability === 'habis', fn ($query) => $query->where('stock', 0))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    public function store(ItemRequest $request)
    {
        $item = Item::create($request->validated());

        ActivityLog::record('create_item', "Menambah barang {$item->name} ({$item->code})");

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    public function update(ItemRequest $request, Item $item)
    {
        $item->update($request->validated());

        ActivityLog::record('update_item', "Mengubah barang {$item->name} ({$item->code})");

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->loans()->whereIn('status', ['menunggu', 'dipinjam', 'terlambat'])->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        $item->delete();

        ActivityLog::record('delete_item', "Menghapus barang {$item->name} ({$item->code})");

        return redirect()->route('items.index')->with('success', 'Barang berhasil diarsipkan.');
    }
}