<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
 
class SupplierSeeder extends Seeder {
    public function run(): void {
        $suppliers = [
            ['name' => 'TechParts PH',    'contact_person' => 'Juan Dela Cruz', 'phone' => '09171234567', 'email' => 'juan@techparts.ph',    'address' => 'Manila, Philippines'],
            ['name' => 'Telecom Supply Co','contact_person' => 'Maria Santos',   'phone' => '09281234567', 'email' => 'maria@telecomsupply.com', 'address' => 'Cebu, Philippines'],
        ];
        foreach ($suppliers as $s) Supplier::create($s);
    }
}
