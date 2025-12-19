<?php

namespace Database\Seeders;

use App\Models\ProductOffer;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductOffersSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Electronics', 'Fashion', 'Home & Garden', 'Books', 'Automotive', 'Health'];
        
        // Get all users
        $users = \App\Models\User::where('role', 'user')->get();
        
        foreach ($users as $user) {
            // Create 1-3 offers per user
            $offerCount = rand(1, 3);
            
            for ($i = 1; $i <= $offerCount; $i++) {
                $status = ['pending', 'approved', 'rejected', 'expired'][rand(0, 3)];
                $expiryDate = Carbon::now()->addDays(rand(-5, 20)); // Some expired, some future
                
                $offer = ProductOffer::create([
                    'user_id' => $user->id,
                    'product_name' => ucfirst($categories[rand(0, 5)]) . ' Product ' . $i,
                    'category' => $categories[rand(0, 5)],
                    'description' => 'High quality product with excellent features. Perfect for daily use.',
                    'price' => rand(50, 1000) + (rand(0, 99) / 100),
                    'discount' => rand(0, 50),
                    'expiry_date' => $expiryDate,
                    'status' => $status,
                    'approved_by_admin_id' => $status === 'approved' || $status === 'rejected' ? 1 : null,
                    'approved_at' => $status === 'approved' || $status === 'rejected' ? Carbon::now()->subDays(rand(1, 7)) : null,
                ]);
                
                // If offer is approved but expiry date passed, mark as expired
                if ($offer->status === 'approved' && $offer->expiry_date < Carbon::now()) {
                    $offer->update(['status' => 'expired']);
                }
            }
        }
        
        $this->command->info('Product offers seeded successfully!');
    }
}