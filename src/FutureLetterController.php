<?php

namespace Buzkall\FutureLetters;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FutureLetterController extends Controller
{
    /**
     * List Future Letters
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $future_letters = FutureLetter::getFutureLettersFromUserId($user_id);
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

        if ($future_letter->user->id == Auth::user()->id) {
            return view('future-letters::edit', compact('future_letters', 'future_letter'));
        }
        return redirect()->route('future-letters.index')->with('error', 'That\'s not yours!');
    }

    public function update(FutureLetterRequest $request, $id)
    {
        $input = $request->validated();
        $future_letter = FutureLetter::findOrFail($id);

        if ($future_letter->user->id == Auth::user()->id) {
            $future_letter->update($input);
            return redirect()->route('future-letters.index')->with('success', 'Updated your future letter!');
        }
        return redirect()->route('future-letters.index')->with('error', 'That\'s not yours!');
    }

    public function destroy($id)
    {
        $future_letter = FutureLetter::findOrFail($id);

        if ($future_letter->user->id == Auth::user()->id) {
            $future_letter->delete();
            return redirect()->route('future-letters.index')->with('warning', 'Deleted future letter!');
        }
        return redirect()->route('future-letters.index')->with('error', 'That\'s not yours!');
    }

}
