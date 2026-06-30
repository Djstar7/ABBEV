<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index()
    {
        $series = Media::where('type', 'series')
            ->visibleTo(auth()->user())
            ->with('category')
            ->latest()
            ->paginate(16);

        return view('series.index', compact('series'));
    }
}
