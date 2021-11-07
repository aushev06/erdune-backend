<?php

namespace App\Blog\Controllers;

use App\Blog\Services\HomeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(private HomeService $homeService) {}

    public function index(Request $request) {
        $this->homeService->getMainInfo();
    }
}
