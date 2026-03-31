<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => [
                    'en' => 'Laskovoe Leto cheese',
                    'ru' => 'Ласковое лето сыр',
                    'tm' => 'Laskowoýe leto peýnir',
                ],
                'file' => 'laskovoe-leto-cheese.jpg',
                'sort_order' => 1,
            ],
            [
                'title' => [
                    'en' => 'Apeti yogurt',
                    'ru' => 'Апети йогурт',
                    'tm' => 'Apeti ýogurt',
                ],
                'file' => 'apeti.jpg',
                'sort_order' => 2,
            ],

        ];

        foreach ($data as $item) {
            $product = Product::create([
                'title' => $item['title'],
                'sort_order' => $item['sort_order'],
            ]);

            $path = 'uploads/products/' . $item['file'];
            $image = public_path($path);

            if (File::exists($image))
                $product->addMedia($image)
                    ->preservingOriginal()
                    ->toMediaCollection('product_image');
            else {
                $this->command->warn('The ' . $path . ' file does not exist.');
            }
        }
    }
}
