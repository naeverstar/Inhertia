<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Franchise;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $franchises = Franchise::all();

        $datas = [];
        for ($i = 1; $i <= count($franchises) ; $i++) { 
            if ($i % 2 == 1) {
                if (!isset($franchises[$i + 1])) {
                    continue;
                }

                $datas[floor($i / 3)] = [
                    [
                        'image' => $franchises[$i - 1]->image,
                        'slug' => $franchises[$i - 1]->slug,
                    ]
                ];
            } else {
                $datas[floor($i / 3)][1] = [
                    'image' => $franchises[$i - 1]->image,
                    'slug' => $franchises[$i - 1]->slug,
                ];
            }
        }

        return view('home', compact('categories', 'datas'));
    }

    public function franchise(Request $request)
    {

        $search = true;
        if ($request->has('category')) {
            $categories = Category::where('slug', $request->category)->with('franchise')->get();
        } else {
            $search = false;
            $categories = Category::with('franchiseLimit')->get();
        }


        return view('franchise', compact('categories', 'search'));
    }

    public function franchiseDetail(Request $request, $slug)
    {
        $franchise = Franchise::where('slug', $slug)->with('category')->get()[0];

        return view('franchiseDetail', compact('franchise'));
    }



    // public function auth ()
    // {
    //     return view('auth.login');
    // }
}
