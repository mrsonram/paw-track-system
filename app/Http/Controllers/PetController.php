<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\MapLocation;
use App\Models\News;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $animals = Animal::get();
        return view('pet/index', compact('animals'));
    }

    public function info(Request $request)
    {
        $species = $request->input('species');
        $status = $request->input('status');

        $animals = Animal::when($species, fn ($query) => $query->where('species', 'like', "%{$species}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('pet/info', compact('animals', 'species', 'status'));
    }

    public function news()
    {
        $news = News::paginate(9);
        return view('pet/news', compact('news'));
    }

    public function map()
    {
        $animals = Animal::get()->concat(MapLocation::get());
        return view('pet/map', compact('animals'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $animals = Animal::findOrFail($id);

        return view('pet/show')->with([
            'data' => $animals
        ]);
    }

    public function message($id)
    {
        $news = News::findOrFail($id);

        return view('pet/news/show')->with([
            'data' => $news
        ]);
    }
}
