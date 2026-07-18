<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\News;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $animals = Pet::get();
        return view('pet/index', compact('animals'));
    }

    public function info()
    {
        $animals = Pet::get()
                        ->sortBy("name");
        return view('pet/info', compact('animals'));
    }

    public function news()
    {
        $news = News::get();
        return view('pet/news', compact('news'));
    }

    public function map()
    {
        $animals = Pet::get();
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
        $animals = Pet::findOrFail($id);

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
