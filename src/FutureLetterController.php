<?php

namespace Buzkall\FutureLetters;

use App\Http\Controllers\Controller;

class FutureLetterController extends Controller
{
    /**
     * List Future Letters
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $future_letters = FutureLetter::getFutureLetters();
        return view('future-letters::list', compact('future_letters'));
    }

    public function store(FutureLetterRequest $request)
    {
        $input = $request->validated();
        FutureLetter::create($input);

        return back()->with('success', 'Future letter prepared to send!');
    }

    public function edit($id)
    {
        $future_letters = FutureLetter::all();
        $future_letter = FutureLetter::findOrFail($id);
        return view('future-letters::edit', compact('future_letters', 'future_letter'));
    }

    public function update(FutureLetterRequest $request, $id)
    {
        $input = $request->validated();
        $future_letter = FutureLetter::findOrFail($id);
        $future_letter->update($input);

        return redirect()->route('future-letters.index')->with('success', 'Updated your future letter!');
    }

    public function destroy($id)
    {
        $future_letter = FutureLetter::findOrFail($id);
        $future_letter->delete();
        return redirect()->route('future-letters.index')->with('success', 'Deleted future letter!');;
    }
}
