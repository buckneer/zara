<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $entries = Inventory::with('variant','user')->orderBy('created_at','desc')->paginate(50);
        return request()->wantsJson() ? response()->json($entries) : view('admin.inventories.index', compact('entries'));
    }

    public function store(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'quantity_change' => 'required|integer',
            'reason' => 'nullable|string|max:191',
            'metadata' => 'nullable|array',
        ]);

        DB::transaction(function () use ($variant, $data, &$entry) {
            $quantity_after = $variant->stock + intval($data['quantity_change']);
            $variant->stock = $quantity_after;
            $variant->save();

            $entry = Inventory::create([
                'variant_id' => $variant->id,
                'quantity_change' => $data['quantity_change'],
                'quantity_after' => $quantity_after,
                'reason' => $data['reason'] ?? null,
                'user_id' => Auth::id(),
                'metadata' => $data['metadata'] ?? null,
            ]);
        });

        return $request->wantsJson() ? response()->json($entry, 201) : redirect()->back()->with('success','Inventory updated.');
    }
}
