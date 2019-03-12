<?php

namespace Buzkall\FutureLetters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FutureLetterController extends Controller
{
    public function index()
    {
        $future_letters = FutureLetter::all();

        return view('future-letters::list', compact('future_letters'));
    }

    public function create()
    {
        $future_letters = FutureLetter::all();
        return view('future-letters::create', compact('future_letters'));
    }

    public function store()
    {
        $input = Request::all();
        FutureLetter::create($input);
        return redirect()->route('future-letters.create');
    }

    public function edit($id)
    {
        $future_letters = FutureLetter::all();
        $future_letter = $future_letters->find($id);
        return view('future-letters::list', compact('future_letters', 'future_letter'));
    }

    public function update($id)
    {
        $input = Request::all();
        $future_letter = FutureLetter::findOrFail($id);
        $future_letter->update($input);
        return redirect()->route('future-letters.list');
    }

    public function destroy($id)
    {
        $future_letter = FutureLetter::findOrFail($id);
        $future_letter->delete();
        return redirect()->route('future-letters.list');
    }
}
