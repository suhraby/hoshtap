<?php

namespace Database\Seeders;

use App\Models\Counter;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $counters = [
            [
                'title' => [
                    'en' => 'Product items',
                    'ru' => 'Продуктовых позиций',
                    'tm' => 'Önümler görnüşi',
                ],
                'number' => 1000,
                'symbol' => '+',
                'sort_order' => 1,
            ],
            [
                'title' => [
                    'en' => 'Years of successful work',
                    'ru' => 'Лет успешной работы',
                    'tm' => 'Üstünlikli iş ýyllary',
                ],
                'number' => 16,
                'symbol' => '+',
                'sort_order' => 2,
            ],
            [
                'title' => [
                    'en' => 'Manufacturers',
                    'ru' => 'Производителей',
                    'tm' => 'Öndürijiler',
                ],
                'number' => 80,
                'symbol' => '+',
                'sort_order' => 3,
            ],
            [
                'title' => [
                    'en' => 'sq.m. of warehouses',
                    'ru' => 'кв.м складов',
                    'tm' => 'kw.m. ammarlar',
                ],
                'number' => 1100,
                'sort_order' => 4,
            ],
        ];

        foreach ($counters as $counter) {
            Counter::create($counter);
        }
    }
}
