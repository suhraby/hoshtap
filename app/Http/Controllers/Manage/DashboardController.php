<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Service;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        $data = [
            [
                'title' => 'Services',
                'count' => Service::count(),
                'to'    => '/manage/services',
            ],
            [
                'title' => 'Manufacturers',
                'count' => Manufacturer::count(),
                'to'    => '/manage/manufacturers',
            ],
            [
                'title' => 'Products',
                'count' => Product::count(),
                'to'    => '/manage/products',
            ],
            [
                'title' => 'Clients',
                'count' => Client::count(),
                'to'    => '/manage/clients',
            ],
        ];

        return Inertia::render('Manage/Index', [
            'data' => $data
        ]);
    }
}
