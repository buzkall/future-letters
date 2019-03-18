<?php

namespace Buzkall\FutureLetters;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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

    /**
     * @param FutureLetterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FutureLetterRequest $request)
    {
        $input = $request->validated();
        FutureLetter::create($input);

        return back()->with('success', 'Future letter prepared to send!');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $future_letters = FutureLetter::all();
        $future_letter = FutureLetter::findOrFail($id);

        if ($future_letter->user->id == Auth::user()->id) {
            return view('future-letters::edit', compact('future_letters', 'future_letter'));
        }
        return redirect()->route('future-letters.index')->with('error', 'That\'s not yours!');
    }

    /**
     * @param FutureLetterRequest $request
     * @param                     $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * DELETE endpoint
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $future_letter = FutureLetter::findOrFail($id);

        if ($future_letter->user->id == Auth::user()->id) {
            $future_letter->delete();
            return redirect()->route('future-letters.index')->with('warning', 'Deleted future letter!');
        }
        return redirect()->route('future-letters.index')->with('error', 'That\'s not yours!');
    }

    /**
     * GET endpoint /cron
     * Will send letters with sending_date before current timestamp
     * and store the sending time in the sent_at field
     */
    public function cron()
    {
        $output = '';
        $future_letters_to_send = FutureLetter::getFutureLettersToSend();
        foreach ($future_letters_to_send as $future_letter_to_send) {

            $output .= 'Sent mail to ' . $future_letter_to_send->email . '<br />';

            $notification = new FutureLetterNotification($future_letter_to_send);
            Notification::send($future_letter_to_send->user, $notification);

            // set to sent in db
            $future_letter_to_send->sent_at = Carbon::now();
            $future_letter_to_send->save();
        }
        if (empty($output)) {
            $output = 'No messages ready to send';
        }
        echo $output;
    }
}
