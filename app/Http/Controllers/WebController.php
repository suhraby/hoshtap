<?php

namespace App\Http\Controllers;

use App\Http\Resources\AboutResource;
use App\Http\Resources\BannerResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\CounterResource;
use App\Http\Resources\ManufacturerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceResource;
use App\Models\About;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Counter;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Service;
use Inertia\Inertia;

class WebController extends Controller
{
    public function index(): \Inertia\Response
    {
        $banners = Banner::orderBy('sort_order')->get();
        $counters = Counter::orderBy('sort_order')->get();
        $about = About::first();
        $services = Service::orderBy('sort_order')->get();
        $manufacturers = Manufacturer::orderBy('sort_order')->get();
        $products = Product::orderBy('sort_order')->get();
        $clients = Client::orderBy('sort_order')->get();

        $contacts = Contact::where('is_active', true)->orderByRaw("
            CASE
                WHEN slug='email' THEN 1
                WHEN slug='phone_number' THEN 2
                WHEN slug='address' THEN 3
                WHEN slug='instagram' THEN 4
                WHEN slug='tiktok' THEN 5
            END
        ")->get();

        $socials = $contacts->whereIn('slug', ['instagram', 'tiktok']);
        $contacts = $contacts->whereNotIn('slug', ['instagram', 'tiktok']);

        return Inertia::render('App/Index', [
            'banners'  => BannerResource::collection($counters),
            'counters' => CounterResource::collection($counters),
            'about'    => $about ? new AboutResource($about) : null,
            'services' => ServiceResource::collection($services),
            'manufacturers'  => ManufacturerResource::collection($manufacturers),
            'products' => ProductResource::collection($products),
            'clients'  => ClientResource::collection($clients),
            'contacts' => ContactResource::collection($contacts),
            'socials'  => ContactResource::collection($socials),
        ]);
    }
}
