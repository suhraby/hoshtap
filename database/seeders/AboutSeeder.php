<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $about = About::create([
            'title' => [
                'en' => 'About the company',
                'ru' => 'О компании',
                'tm' => 'Kompaniýa barada',
            ],
            'body' => [
                'en' => 'Hostap was founded in 2010 and is a distributor and supplier of food products:',
                'ru' => 'Hostap — основан в 2010 году и является дистрибьютором и поставщиком продуктов питания:',
                'tm' => 'Hostap 2010-njy ýylda esaslandyryldy we azyk önümleriniň distribýutory we üpjün edijisidir:',
            ],
            'context' => [
                'en' => 'from retail customers to large wholesale partners and chain stores. In the Turkmen market, we represent products manufactured in Belarus, the Russian Federation, Lithuania, the Islamic Republic of Iran, Norway, Chile, and China.',
                'ru' => 'от розничных клиентов до крупных оптовых партнёров и сетевых магазинов. На рынке Туркменистана мы представляем товары, произведённые в Беларуси, Российской Федерации, Литве, Исламской Республике Иран, Норвегии, Чили и Китае.',
                'tm' => 'bölek söwda müşderilerinden iri lomaý söwda hyzmatdaşlaryna we dükanlar ulgamyna çenli. Türkmeistan bazarynda biz Belarusda, Russiýa Federasiýasynda, Litwada, Eýran Yslam Respublikasynda, Norwegiýada, Çilide we Hytaýda öndürilen önümleri hödürleýäris.',
            ],
            'market_title' => [
                'en' => 'Product range',
                'ru' => 'Ассортимент продукции',
                'tm' => 'Önümleriň görnüşi',
            ],
            'product_range' => [
                'en' => 'Our range of food products includes dairy products and cheeses, seafood, confectionery and bakery goods, as well as products needed for cafes, restaurants, and bars. We deliver all of these products to all regions of Turkmenistan.',
                'ru' => 'Ассортимент поставляемых продуктов питания включает молочную продукцию и сыры, морепродукты, кондитерские и хлебобулочные изделия, а также продукты, необходимые для кафе, ресторанов и баров. Мы доставляем всю эту продукцию во все регионы Туркменистана.',
                'tm' => 'Azyk önümlerimiziň görnüşleri süýt önümleri we peýnirler, deňiz önümleri, süýji we çörek önümleri, şeýle hem kafeler, restoranlar we barlar üçin zerur bolan önümleri öz içine alýar. Bu önümleriň hemmesini Türkmenistanyň ähli sebitlerine eltip berýäris.',
            ],
        ]);

        $warehouse_img_path = 'uploads/about/about_warehouse.jpg';
        $warehouse_image = public_path($warehouse_img_path);
        $market_img_path = 'uploads/about/market.jpg';
        $market_image = public_path($market_img_path);

        if (File::exists($warehouse_image)) {
            $about->addMedia($warehouse_image)
                ->preservingOriginal()
                ->toMediaCollection('about_image');
        } else {
            $this->command->warn('The ' . $warehouse_img_path . ' file does not exist.');
        }

        if (File::exists($market_image)) {
            $about->addMedia($market_image)
                ->preservingOriginal()
                ->toMediaCollection('market_image');
        } else {
            $this->command->warn('The ' . $market_img_path . ' file does not exist.');
        }
    }
}
