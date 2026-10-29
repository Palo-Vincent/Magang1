<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Str;

class ArchiveController extends Controller
{
    private const TYPES = [
        'items' => [Item::class, 'Barang'],
        'categories' => [Category::class, 'Kategori'],
        'users' => [User::class, 'Pengguna'],
    ];

    public function index(string $type)
    {
        [$model, $label] = self::TYPES[$type];

        $records = $model::onlyTrashed()->latest('deleted_at')->paginate(10);

        return view('archive.index', compact('records', 'type', 'label'));
    }

    public function restore(string $type, int $id)
    {
        [$model, $label] = self::TYPES[$type];

        $record = $model::onlyTrashed()->findOrFail($id);

        if ($record instanceof Item && $record->category->trashed()) {
            return back()->with('error', 'Pulihkan kategori "'.$record->category->name.'" terlebih dahulu.');
        }

        $record->restore();

        ActivityLog::record('restore_'.Str::singular($type), "Memulihkan {$label} {$record->name}");

        return back()->with('success', "{$label} berhasil dipulihkan.");
    }
}