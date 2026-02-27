<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // ── Date Range ──────────────────────────────────────────────────
        $from = $request->from
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $to = $request->to
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        // ── Base Query ───────────────────────────────────────────────────
        $query = StockTransaction::with(['product.category', 'user'])
            ->whereBetween('created_at', [$from, $to]);

        // Optional filters
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // ── Summary Numbers ──────────────────────────────────────────────
        $summaryQuery = StockTransaction::whereBetween('created_at', [$from, $to]);
        if ($request->product_id) $summaryQuery->where('product_id', $request->product_id);
        if ($request->type)       $summaryQuery->where('type', $request->type);

        $totalStockIn  = (clone $summaryQuery)->where('type', 'stock_in')->sum('quantity');
        $totalStockOut = (clone $summaryQuery)->where('type', 'stock_out')->sum('quantity');
        $totalValue    = (clone $summaryQuery)->whereNotNull('unit_price')
                            ->selectRaw('SUM(quantity * unit_price) as total')
                            ->value('total') ?? 0;

        // ── Paginated Transactions ────────────────────────────────────────
        $transactions = $query->latest()->paginate(25);

        // ── Top Moving Products ───────────────────────────────────────────
        $topMovingProducts = Product::withCount([
            'transactions as total_movements' => function ($q) use ($from, $to, $request) {
                $q->whereBetween('created_at', [$from, $to]);
                if ($request->type) $q->where('type', $request->type);
            }
        ])->orderByDesc('total_movements')->take(10)->get();

        // ── Low Stock Products ────────────────────────────────────────────
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('quantity')
            ->get();

        // ── Daily Activity Data (for bar chart) ───────────────────────────
        $dailyData = collect();

        // Only build daily data if range is 90 days or less to keep it readable
        $daysDiff = $from->diffInDays($to);
        if ($daysDiff <= 90) {
            $rawDaily = DB::table('stock_transactions')
                ->selectRaw("DATE(created_at) as date,
                    SUM(CASE WHEN type = 'stock_in'  THEN quantity ELSE 0 END) as stock_in,
                    SUM(CASE WHEN type = 'stock_out' THEN quantity ELSE 0 END) as stock_out")
                ->whereBetween('created_at', [$from, $to]);

            if ($request->product_id) {
                $rawDaily->where('product_id', $request->product_id);
            }

            $rawDailyData = $rawDaily
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            // Fill in all dates in range (so days with 0 also show)
            $period = new \DatePeriod($from, new \DateInterval('P1D'), $to->copy()->addDay());
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $dailyData->push((object)[
                    'date'      => $dateStr,
                    'stock_in'  => $rawDailyData[$dateStr]->stock_in  ?? 0,
                    'stock_out' => $rawDailyData[$dateStr]->stock_out ?? 0,
                ]);
            }
        }

        // ── All Products for filter dropdown ─────────────────────────────
        $allProducts = Product::where('is_active', true)->orderBy('name')->get();

        return view('reports.index', compact(
            'from', 'to',
            'totalStockIn', 'totalStockOut', 'totalValue',
            'transactions',
            'topMovingProducts',
            'lowStockProducts',
            'dailyData',
            'allProducts'
        ));
    }
}