<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            DB::table('inventory')->delete();
            DB::table('products')->delete();

            $now = Carbon::now();

            $products = [
                [
                    'name' => 'Laptop Ultraligera 13"',
                    'description' => 'Potente laptop con procesador de última generación y 16GB RAM.',
                    'price' => 1250.99,
                    'sku' => 'LT-UL-13-A',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Monitor Curvo 27" 4K',
                    'description' => 'Monitor de alta resolución ideal para diseño gráfico y gaming.',
                    'price' => 459.50,
                    'sku' => 'MN-CRV-27-B',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Teclado Mecánico RGB',
                    'description' => 'Teclado con switches táctiles y retroiluminación RGB personalizable.',
                    'price' => 89.90,
                    'sku' => 'TC-MEC-RGB-C',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ];

            DB::table('products')->insert($products);

            $productIds = DB::table('products')
                ->whereIn('sku', array_column($products, 'sku'))
                ->pluck('id', 'sku');

            $inventoryRows = [
                [
                    'product_id' => $productIds['LT-UL-13-A'],
                    'stock_quantity' => 15,
                    'last_stock_update' => $now,
                ],
                [
                    'product_id' => $productIds['MN-CRV-27-B'],
                    'stock_quantity' => 30,
                    'last_stock_update' => $now,
                ],
                [
                    'product_id' => $productIds['TC-MEC-RGB-C'],
                    'stock_quantity' => 120,
                    'last_stock_update' => $now,
                ],
            ];

            DB::table('inventory')->insert($inventoryRows);
        });
    }
}
