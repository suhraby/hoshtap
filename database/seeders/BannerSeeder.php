<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => [
                    'en' => 'We import and supply food products',
                    'ru' => 'Импортируем и поставляем продукты питания',
                    'tm' => 'Biz azyk önümlerini import edýäris we üpjün edýäris',
                ],
                'file' => 'banner1.jpg',
                'sort_order' => 1,
            ],
            [
                'title' => [
                    'en' => 'We supply dairy products and cheeses.',
                    'ru' => 'Мы поставляем молочную продукцию и сыры.',
                    'tm' => 'Biz süýt önümlerini we peýnirleri getirýäris.',
                ],
                'file' => 'banner2.jpg',
                'sort_order' => 2,
            ],

        ];

        foreach ($data as $item) {
            $banner = Banner::create([
                'title' => $item['title'],
                'sort_order' => $item['sort_order'],
            ]);

            $path = 'uploads/banners/' . $item['file'];
            $image = public_path($path);

            if (File::exists($image))
                $banner->addMedia($image)
                    ->preservingOriginal()
                    ->toMediaCollection('banner_image');
            else {
                $this->command->warn('The ' . $path . ' file does not exist.');
            }
        }
    }
}
