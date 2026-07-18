<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoogleMap;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;

class GoogleMapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maps = GoogleMap::all();

        $dataMap  = array();
        $dataMap['type'] = 'FeatureCollection';
        $dataMap['features'] = array();
        foreach ($maps as $value) {
            $feaures = array();
            $feaures['type'] = 'Feature';
            $geometry = array("type" => "Point", "coordinates" => [$value->lng, $value->lat]);
            $feaures['geometry'] = $geometry;
            $properties = array('name' => $value->name, "location" => $value->location);
            $feaures['properties'] = $properties;
            array_push($dataMap['features'], $feaures);
        }
        return view('pages/google-map')->with('dataArray', json_encode($dataMap));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        GoogleMap::create($request->all());
        return redirect('/map')->with('success', "Add map success!");
    }

    public function add(Request $request)
    {
        GoogleMap::create($request->all());
        return redirect('/google/add')->with('success', "Add map success!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $animals = GoogleMap::find($id);
        return view('google/view', compact('animals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
