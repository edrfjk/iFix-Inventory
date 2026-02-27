<?php
namespace App\Http\Controllers;
use App\Models\LowStockAlert;
use Illuminate\Http\Request;
 
class AlertController extends Controller {
 
    public function index() {
        $alerts = LowStockAlert::with('product')
            ->orderBy('is_read')->latest()->paginate(20);
        return view('alerts.index', compact('alerts'));
    }
 
    public function markRead($id) {
        LowStockAlert::findOrFail($id)->update(['is_read' => true]);
        return back()->with('success', 'Alert marked as read.');
    }
 
    public function markAllRead() {
        LowStockAlert::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All alerts marked as read.');
    }
}