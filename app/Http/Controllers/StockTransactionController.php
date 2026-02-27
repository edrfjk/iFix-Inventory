<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\LowStockAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('reference_no', 'like', "%{$request->search}%")
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$request->search}%")
                                                        ->orWhere('sku',  'like', "%{$request->search}%"));
            });
        }

        // Summary numbers for the filtered set
        $stockInTotal  = (clone $query)->where('type', 'stock_in')->sum('quantity');
        $stockOutTotal = (clone $query)->where('type', 'stock_out')->sum('quantity');

        $transactions = $query->latest()->paginate(20);

        return view('transactions.index', compact('transactions', 'stockInTotal', 'stockOutTotal'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:stock_in,stock_out',
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'remarks'    => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->type === 'stock_out' && $product->quantity < $request->quantity) {
            return back()
                ->withErrors(['quantity' => "Insufficient stock. Available: {$product->quantity} {$product->unit}"])
                ->withInput();
        }

        DB::transaction(function () use ($request, $product) {
            // Update stock quantity
            if ($request->type === 'stock_in') {
                $product->increment('quantity', $request->quantity);
            } else {
                $product->decrement('quantity', $request->quantity);
            }

            // Record transaction
            StockTransaction::create([
                'product_id'   => $request->product_id,
                'user_id'      => auth()->id(),
                'type'         => $request->type,
                'quantity'     => $request->quantity,
                'unit_price'   => $request->unit_price,
                'remarks'      => $request->remarks,
                'reference_no' => 'TXN-' . strtoupper(uniqid()),
            ]);

            // Check low stock and create alert if needed
            $product->refresh();
            if ($product->isLowStock()) {
                LowStockAlert::firstOrCreate([
                    'product_id' => $product->id,
                    'is_read'    => false,
                ]);
            }
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction recorded successfully!');
    }

    public function show(StockTransaction $transaction)
    {
        $transaction->load(['product.category', 'user']);
        return view('transactions.show', compact('transaction'));
    }
}