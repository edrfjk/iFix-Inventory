<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\LowStockAlert;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
 
class DashboardController extends Controller {
    public function index() {
        $totalProducts      = Product::where('is_active', true)->count();
        $totalStock         = Product::where('is_active', true)->sum('quantity');
        $lowStockCount      = Product::where('is_active', true)
                                ->whereColumn('quantity', '<=', 'low_stock_threshold')->count();
        $outOfStockCount    = Product::where('is_active', true)->where('quantity', 0)->count();
        $totalCategories    = Category::count();
        $totalSuppliers     = Supplier::count();
        $todayStockIn       = StockTransaction::where('type', 'stock_in')
                                ->whereDate('created_at', today())->sum('quantity');
        $todayStockOut      = StockTransaction::where('type', 'stock_out')
                                ->whereDate('created_at', today())->sum('quantity');
        $recentTransactions = StockTransaction::with(['product', 'user'])
                                ->latest()->take(8)->get();
        $lowStockProducts   = Product::with('category')
                                ->where('is_active', true)
                                ->whereColumn('quantity', '<=', 'low_stock_threshold')
                                ->orderBy('quantity')->take(6)->get();
        $unreadAlerts       = LowStockAlert::where('is_read', false)->count();
 
        return view('dashboard.index', compact(
            'totalProducts', 'totalStock', 'lowStockCount', 'outOfStockCount',
            'totalCategories', 'totalSuppliers', 'todayStockIn', 'todayStockOut',
            'recentTransactions', 'lowStockProducts', 'unreadAlerts'
        ));
    }
}
