<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Product extends Model {
    protected $fillable = [
        'name', 'sku', 'description', 'category_id', 'supplier_id',
        'cost_price', 'selling_price', 'quantity', 'low_stock_threshold',
        'unit', 'is_active'
    ];
 
    protected $casts = ['is_active' => 'boolean'];
 
    public function category() { return $this->belongsTo(Category::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function transactions() { return $this->hasMany(StockTransaction::class); }
    public function alerts() { return $this->hasMany(LowStockAlert::class); }
 
    public function isLowStock(): bool {
        return $this->quantity <= $this->low_stock_threshold;
    }
 
    public function getStockStatusAttribute(): string {
        if ($this->quantity <= 0) return 'out_of_stock';
        if ($this->isLowStock()) return 'low_stock';
        return 'in_stock';
    }
}
