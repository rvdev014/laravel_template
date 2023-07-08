<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RbacSeeder::class);

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'first_name' => 'User',
                'last_name' => 'Test',
                'email' => 'test@example.com',
                'birth_date' => '1990-01-01',
                'password_hash' => Hash::make('12345678'),
            ]);
        }
    }
}
