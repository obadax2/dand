<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class CharacterController extends Controller
{
    public function myCharacters()
    {
        $stories = Story::with('characters')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('characters.my', compact('stories'));
    }
}