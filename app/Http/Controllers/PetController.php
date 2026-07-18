<?php

namespace App\Http\Controllers;

use App\Models\Animal;
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
        $animals = Animal::get();
        return view('pet/index', compact('animals'));
    }

    public function info()
    {
        $animals = Animal::get()
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
        $animals = Animal::get();
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
