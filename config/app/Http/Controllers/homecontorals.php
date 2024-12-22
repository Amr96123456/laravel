<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Product;
use App\Models\Category;
class homecontorals extends Controller
{

    public function index()
    {
        $slides =Slide::where('status',1)->get()->take(3);
        $categories =Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price','<>','')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured',1)->get()->take(8);
        return view('index' ,compact('slides','categories','sproducts'));
    }
}
