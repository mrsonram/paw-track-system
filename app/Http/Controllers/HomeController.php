<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Contact;
use App\Models\News;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'total_dogs' => Animal::count(),
            'alive_dogs' => Animal::where('status', 'มีชีวิตอยู่')->count(),
            'deceased_dogs' => Animal::where('status', 'เสียชีวิตแล้ว')->count(),
            'news_count' => News::count(),
            'contact_count' => Contact::count(),
        ];

        return view('admin/home', compact('stats'));
    }
}
