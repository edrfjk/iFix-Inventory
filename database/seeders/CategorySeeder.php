<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
 
class CategorySeeder extends Seeder {
    public function run(): void {
        $categories = [
            ['name' => 'Smartphone Parts',    'description' => 'Screens, batteries, buttons'],
            ['name' => 'Network Equipment',   'description' => 'Routers, switches, cables'],
            ['name' => 'Accessories',         'description' => 'Cases, chargers, cables'],
            ['name' => 'SIM & Memory Cards',  'description' => 'SIM cards and storage'],
            ['name' => 'Tools & Equipment',   'description' => 'Repair tools and equipment'],
        ];
        foreach ($categories as $cat) Category::create($cat);
    }
}
