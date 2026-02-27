<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class LowStockAlert extends Model {
    protected $fillable = ['product_id', 'is_read'];
    protected $casts = ['is_read' => 'boolean'];
    public function product() { return $this->belongsTo(Product::class); }
}
