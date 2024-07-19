<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('genre')->get();
        $genres = Genre::all();
        return view('index', compact('shops', 'genres'));
    }
    
    public function detail()
    {
        $shops = Shop::with('genre')->get();
        $genres = Genre::all();
        return view('detail', compact('shops', 'genres'));
    }

    public function search(Request $request)
    {
        if ($request->has('reset')) {
            return redirect('/')->withInput();
        }

        $query = Shop::query();
        $query = $this->getSearchQuery($request, $query);
        $shops = $query->get();
        $genres = Genre::all();
        return view('index', compact('shops', 'genres'));
    }

    private function getSearchQuery($request, $query)
    {
        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if (!empty($request->pref)) {
            $query->where('area', '=', $request->pref);
        }

        if (!empty($request->genre_id)) {
            $query->where('genre_id', '=', $request->genre_id);
        }

        return $query;
    }
}
