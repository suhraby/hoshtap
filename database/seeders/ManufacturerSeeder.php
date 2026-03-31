<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => [
                    'en' => 'Savushkin Horeca',
                    'ru' => 'Савушкин HoReCa',
                    'tm' => 'Sawuşkin Horeka',
                ],
                'file' => 'savushkin-horeca.jpg',
                'sort_order' => 1,
            ],
            [
                'title' => [
                    'en' => 'Laskovoe Leto',
                    'ru' => 'Ласковое лето',
                    'tm' => 'Laskowoýe leto',
                ],
                'file' => 'laskovoe-leto.jpg',
                'sort_order' => 2,
            ],
            [
                'title' => [
                    'en' => 'Delfood',
                    'ru' => 'Делфуд',
                    'tm' => 'Delfud',
                ],
                'file' => 'delfood.jpg',
                'sort_order' => 3,
            ],
            [
                'title' => [
                    'en' => 'Tvorobushki',
                    'ru' => 'Творобушки',
                    'tm' => 'Tworobuşki',
                ],
                'file' => 'tvorobushki.jpg',
                'sort_order' => 4,
            ],
            [
                'title' => [
                    'en' => 'Berezka',
                    'ru' => 'Березка',
                    'tm' => 'Berezka',
                ],
                'file' => 'berezka.jpg',
                'sort_order' => 5,
            ],
            [
                'title' => [
                    'en' => 'Savushkin',
                    'ru' => 'Савушкин',
                    'tm' => 'Sawuşkin',
                ],
                'file' => 'savushkin.jpg',
                'sort_order' => 6,
            ],
            [
                'title' => [
                    'en' => 'Sveza',
                    'ru' => 'Свежа',
                    'tm' => 'Sweža',
                ],
                'file' => 'sveza.jpg',
                'sort_order' => 7,
            ],
            [
                'title' => [
                    'en' => 'Top',
                    'ru' => 'Топ',
                    'tm' => 'Top',
                ],
                'file' => 'top.jpg',
                'sort_order' => 8,
            ],
            [
                'title' => [
                    'en' => 'Svezhest',
                    'ru' => 'Свежесть',
                    'tm' => 'Swežest',
                ],
                'file' => 'svezhest.jpg',
                'sort_order' => 9,
            ],
            [
                'title' => [
                    'en' => 'Exponenta',
                    'ru' => 'Экспонента',
                    'tm' => 'Eksponenta',
                ],
                'file' => 'exponenta.jpg',
                'sort_order' => 10,
            ],
            [
                'title' => [
                    'en' => 'Brest Litovsk',
                    'ru' => 'Брест-Литовск',
                    'tm' => 'Brest-Litowsk',
                ],
                'file' => 'brest-litovsk.jpg',
                'sort_order' => 11,
            ],
            [
                'title' => [
                    'en' => 'Pljush',
                    'ru' => 'Плюш',
                    'tm' => 'Plýuş',
                ],
                'file' => 'pljush.jpg',
                'sort_order' => 12,
            ],
        ];

        foreach ($data as $item) {
            $manufacturer = Manufacturer::create([
                'title' => $item['title'],
                'sort_order' => $item['sort_order'],
            ]);

            $path = 'uploads/manufacturers/' . $item['file'];
            $image = public_path($path);

            if (File::exists($image))
                $manufacturer->addMedia($image)
                    ->preservingOriginal()
                    ->toMediaCollection('manufacturer_image');
            else {
                $this->command->warn('The ' . $path . ' file does not exist.');
            }
        }
    }
}
