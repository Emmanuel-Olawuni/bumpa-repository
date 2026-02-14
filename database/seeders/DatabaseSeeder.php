<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if already seeded
        if (Merchant::count() > 0) {
            return;
        }

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $fashion = Merchant::firstOrCreate(
            ['slug' => 'sarahs-styles'],
            [
                'name' => "Sarah's Styles",
                'email' => 'sarah@sarahsstyles.com',
                'phone' => '+234 801 234 5678',
                'description' => 'Premium African fashion and accessories',
                'logo' => 'https://ui-avatars.com/api/?name=Sarahs+Styles&background=FF6B9D&color=fff&size=200',
            ]
        );

        $beauty = Merchant::firstOrCreate(
            ['slug' => 'glam-beauty'],
            [
                'name' => 'Glam Beauty',
                'email' => 'hello@glambeauty.ng',
                'phone' => '+234 802 345 6789',
                'description' => 'Your one-stop beauty shop',
                'logo' => 'https://ui-avatars.com/api/?name=Glam+Beauty&background=C44569&color=fff&size=200',
            ]
        );

        $electronics = Merchant::firstOrCreate(
            ['slug' => 'techhub-ng'],
            [
                'name' => 'TechHub NG',
                'email' => 'info@techhub.ng',
                'phone' => '+234 803 456 7890',
                'description' => 'Latest gadgets and electronics',
                'logo' => 'https://ui-avatars.com/api/?name=TechHub+NG&background=1E3799&color=fff&size=200',
            ]
        );

        // Fashion products
        $fashionProducts = [
            [
                'name' => 'Blue Ankara Dress',
                'slug' => 'blue-ankara-dress',
                'description' => 'Beautiful ankara dress with vibrant blue patterns.',
                'price' => 15000,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800&q=80',
                'category' => 'Dresses',
            ],
            [
                'name' => 'Red Evening Gown',
                'slug' => 'red-evening-gown',
                'description' => 'Elegant red evening gown for formal events.',
                'price' => 25000,
                'stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?w=800&q=80',
                'category' => 'Dresses',
            ],
            [
                'name' => 'Denim Jacket',
                'slug' => 'denim-jacket',
                'description' => 'Classic denim jacket. Fits perfectly.',
                'price' => 12000,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&q=80',
                'category' => 'Jackets',
            ],
            [
                'name' => 'Black Leather Shoes',
                'slug' => 'black-leather-shoes',
                'description' => 'Premium black leather shoes for men.',
                'price' => 18000,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1533867617858-e7b97e060509?w=800&q=80',
                'category' => 'Shoes',
            ],
            [
                'name' => 'White Sneakers',
                'slug' => 'white-sneakers',
                'description' => 'Comfortable white sneakers for everyday wear.',
                'price' => 14000,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&q=80',
                'category' => 'Shoes',
            ],
        ];

        foreach ($fashionProducts as $product) {
            $fashion->products()->firstOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }

        // Beauty products
        $beautyProducts = [
            [
                'name' => 'Moisturizing Face Cream',
                'slug' => 'moisturizing-face-cream',
                'description' => 'Hydrating face cream for all skin types.',
                'price' => 5500,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&q=80',
                'category' => 'Skincare',
            ],
            [
                'name' => 'Matte Lipstick Set',
                'slug' => 'matte-lipstick-set',
                'description' => 'Set of 5 matte lipsticks in various shades.',
                'price' => 8000,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=800&q=80',
                'category' => 'Makeup',
            ],
            [
                'name' => 'Hair Growth Oil',
                'slug' => 'hair-growth-oil',
                'description' => 'Natural hair growth oil with essential vitamins.',
                'price' => 6500,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=800&q=80',
                'category' => 'Haircare',
            ],
        ];

        foreach ($beautyProducts as $product) {
            $beauty->products()->firstOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }

        // Electronics products
        $electronicsProducts = [
            [
                'name' => 'Wireless Earbuds',
                'slug' => 'wireless-earbuds',
                'description' => 'Bluetooth wireless earbuds with noise cancellation.',
                'price' => 12500,
                'stock' => 18,
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&q=80',
                'category' => 'Audio',
            ],
            [
                'name' => 'Phone Stand & Charger',
                'slug' => 'phone-stand-charger',
                'description' => '2-in-1 phone stand with wireless charging.',
                'price' => 8500,
                'stock' => 22,
                'image' => 'https://images.unsplash.com/photo-1588423771073-b8903fbb85b5?w=800&q=80',
                'category' => 'Accessories',
            ],
            [
                'name' => 'Portable Power Bank 20000mAh',
                'slug' => 'portable-power-bank',
                'description' => 'High capacity power bank with fast charging.',
                'price' => 15000,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=800&q=80',
                'category' => 'Accessories',
            ],
            [
                'name' => 'LED Desk Lamp',
                'slug' => 'led-desk-lamp',
                'description' => 'Adjustable LED desk lamp with multiple brightness levels.',
                'price' => 9500,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1565006902735-2bb4ee6b065f?w=800&q=80',
                'category' => 'Lighting',
            ],
        ];

        foreach ($electronicsProducts as $product) {
            $electronics->products()->firstOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
