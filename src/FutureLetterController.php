<?php

namespace Buzkall\FutureLetters;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Notification;

class FutureLetterController extends Controller
{

    /**
     * List Future Letters
     *
     * @return View
     */
    public function index()
    {
        if (Auth::id()) {
            $user_id = Auth::user()->id;
            $future_letters = FutureLetter::getFutureLettersFromUserId($user_id);
        } else {
            $future_letters = [];
        }
        return view('future-letters::list', compact('future_letters'));
    }

    /**
     * @param FutureLetterRequest $request
     * @return RedirectResponse
     */
    public function store(FutureLetterRequest $request)
    {
        $input = $request->validated();
        FutureLetter::create($input);

        return back()->with('success', 'Future letter prepared to be sent!');
    }

    /**
     * @param $id
     * @return View
     */
    public function edit($id)
    {
        $future_letters = FutureLetter::all();
        $future_letter = FutureLetter::findOrFail($id);

        if ($this->userIsOwner($future_letter)) {
            return view('future-letters::edit', compact('future_letters', 'future_letter'));
        }
        return redirect()->route('future-letters.index')->with('error', 'That\'s not yours!');
    }

    /**
     * @param FutureLetterRequest $request
     * @param                     $id
     * @return RedirectResponse
     */
    public function update(FutureLetterRequest $request, $id)
    {
        $input = $request->validated();
        $future_letter = FutureLetter::findOrFail($id);

        if ($this->userIsOwner($future_letter)) {
            $future_letter->update($input);
            return redirect()->route('future-letters.edit', $id)->with('success', 'Your future letter has been updated!');
        }
        return redirect()->route('future-letters.index')->with('error', 'Hey, that\'s not yours!');
    }

    /**
     * DELETE endpoint
     *
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $future_letter = FutureLetter::findOrFail($id);

        if ($this->userIsOwner($future_letter)) {
            $future_letter->delete();
            return redirect()->route('future-letters.index')->with('error', 'Your future letter has been deleted!');
        }
        return redirect()->route('future-letters.index')->with('error', 'Hey, that\'s not yours!');
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

            $output .= 'Mail sent to ' . $future_letter_to_send->email . '<br />';

            $notification = new FutureLetterNotification($future_letter_to_send);
            Notification::send($future_letter_to_send, $notification);

            // set to sent in db
            $future_letter_to_send->sent_at = Carbon::now();
            $future_letter_to_send->save();
        }
        if (empty($output)) {
            $output = 'No letters ready to send';
        }
        echo $output;
    }

    /**
     * @param $future_letter
     * @return bool
     */
    public function userIsOwner($future_letter)
    {
        return $future_letter->user->id === Auth::user()->id;
    }
}
