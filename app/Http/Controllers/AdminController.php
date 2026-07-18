<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $q = $request->input('q');

        $animals = Animal::when($q, function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where("name", "like", "%{$q}%")
                        ->orWhere("species", "like", "%{$q}%")
                        ->orWhere("marking", "like", "%{$q}%")
                        ->orWhere("gender", "like", "%{$q}%")
                        ->orWhere("collar", "like", "%{$q}%")
                        ->orWhere("age", "like", "%{$q}%")
                        ->orWhere("status", "like", "%{$q}%")
                        ->orWhere("vet", "like", "%{$q}%")
                        ->orWhere("owner", "like", "%{$q}%")
                        ->orWhere("location", "like", "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin/dogs/dog', compact('animals', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $animals = Animal::get();

        return view('admin/dogs/create', compact('animals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'marking' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'collar' => 'required|string|max:255',
            'age' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'vet' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        $requestData = $request->only([
            'name', 'species', 'marking', 'gender', 'collar', 'age',
            'status', 'vet', 'owner', 'location', 'lat', 'lng',
        ]);
        $requestData['image'] = $request->file('image')->store('images', 'public');

        Animal::create($requestData);

        return redirect('dog')->with('flash_message', 'Book added!');
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

        return view('admin/dogs/show', compact('animals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $animals = Animal::findOrFail($id);

        return view('admin/dogs/edit', compact('animals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'marking' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'collar' => 'required|string|max:255',
            'age' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'vet' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $requestData = $request->only([
            'name', 'species', 'marking', 'gender', 'collar', 'age',
            'status', 'vet', 'owner', 'location', 'lat', 'lng',
        ]);

        if ($request->hasFile('image')) {
            $requestData['image'] = $request->file('image')->store('images', 'public');
        }

        $animals = Animal::findOrFail($id);
        $animals->update($requestData);

        return redirect('dog')->with('ข้อความ', 'อัพเดตข้อมูลแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Animal::destroy($id);

        return redirect('dog');
    }
}
