<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::available()
            ->with('category')
            ->when($request->q, fn ($query, $search) => $query->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
            }))
            ->when($request->category_id, fn ($query, $value) => $query->where('category_id', $value))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('catalog.index', compact('items', 'categories'));
    }
}