<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller {

    public function index(Request $request) {
        $query = Product::with(['category', 'supplier'])->where('is_active', true);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->category_id) $query->where('category_id', $request->category_id);
if ($request->status === 'low_stock') {
    $query->where('quantity', '>', 0)
          ->whereColumn('quantity', '<=', 'low_stock_threshold');
} elseif ($request->status === 'out_of_stock') {
    $query->where('quantity', 0);
}

        $products   = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create() {
        $categories = Category::all();
        $suppliers  = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request) {
        $request->validate([
    'name' => 'required|string|max:255',
    'sku' => 'required|string|max:255|unique:products,sku',
    'unit' => 'required|string|max:50',
    'category_id' => 'required|exists:categories,id',
    'supplier_id' => 'required|exists:suppliers,id',
    'description' => 'required|string',
    'cost_price' => 'required|numeric|min:0',
    'selling_price' => 'required|numeric|min:0',
    'quantity' => 'required|integer|min:0',
    'low_stock_threshold' => 'required|integer|min:1',
]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    public function show(Product $product) {
        $product->load(['category', 'supplier', 'transactions.user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product) {
        $categories = Category::all();
        $suppliers  = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product) {
        $request->validate([
            'name'               => 'required|string|max:255',
            'sku'                => 'required|string|unique:products,sku,' . $product->id,
            'category_id'        => 'nullable|exists:categories,id',
            'supplier_id'        => 'nullable|exists:suppliers,id',
            'cost_price'         => 'required|numeric|min:0',
            'selling_price'      => 'required|numeric|min:0',
            'low_stock_threshold'=> 'required|integer|min:1',
            'unit'               => 'required|string',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product) {
        $product->delete(); // permanent delete
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}