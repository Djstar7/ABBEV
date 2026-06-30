<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        $films = Media::where('type', 'movie')
            ->visibleTo(auth()->user())
            ->with('category')
            ->latest()
            ->paginate(16);

        return view('films.index', compact('films'));
    }
}
