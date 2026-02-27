<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
 
class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name'     => 'iFix Owner',
            'email'    => 'owner@ifix.com',
            'password' => Hash::make('ifix2025'),
            'role'     => 'owner',
        ]);
    }
}
