<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        if(!empty($search))
        {
            $contacts = Contact::where("name", "like", "%{$search}%")
                            ->orWhere("email", "like", "%{$search}%")
                            ->orWhere("title", "like", "%{$search}%")
                            ->orWhere("message", "like", "%{$search}%")
                            ->get();;
        }
        else
        {
            $contacts = Contact::get();
        }
        return view('admin/contacts/contact', compact('contacts', 'search'));
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
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'title' => 'required',
            'message' => 'required',
        ]);

        $data = new Contact;

        $data->name = $request->input('name');
        $data->email = $request->input('email');
        $data->title = $request->input('title');
        $data->message = $request->input('message');
        $data->save();

        return redirect('/')->with('success', 'ส่งข้อมูลแล้ว');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin/contacts/view', compact('contact'));
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
        Contact::destroy($id);

        return redirect('contact');
    }
}
