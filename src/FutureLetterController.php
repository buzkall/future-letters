<?php

namespace Buzkall\FutureLetters;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
        $future_letters = [];
        if ($user_id = Auth::id()) {
            $future_letters = FutureLetter::getFutureLettersFromUserId($user_id);
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

        // anti spam measures. In case someone tries to send a bunch of verify emails
        // to someone else, only send one each day
        if (FutureLetter::getNumberOfUnverifiedEmailsSentToEmail($input['email']) > 0) {
            $message = 'Too many unverified emails sent to that email';
            return back()->with('error', $message);
        }

        $future_letter = FutureLetter::create($input);
        $message = 'Future letter prepared to be sent!';
        if (Auth::guest()) {
            $future_letter->sendEmailVerificationNotification();
            $message .= 'We\'ve sent you an email so you can verify you own this email.';
        }

        return back()->with('success', $message);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        if (!isset($request->id)) {
            throw new AuthorizationException;
        }

        $future_letter = FutureLetter::findOrFail($request->id);

        if (!$future_letter->hasVerifiedEmail()) {
            $future_letter->markEmailAsVerified();
        }

        $message = 'Thank you for verifying your email.';
        return redirect($this->redirectPath($request->id))->with('success', $message);
    }


    /**
     * @param $id
     * @return View
     */
    public function edit($id)
    {
        $future_letters = FutureLetter::getFutureLettersFromUserId(Auth::id());
        $future_letter = FutureLetter::findOrFail($id);

        if ($future_letter->userIsOwner()) {
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

        if ($future_letter->userIsOwner()) {
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
     * @throws Exception
     */
    public function destroy($id)
    {
        $future_letter = FutureLetter::findOrFail($id);

        if ($future_letter->userIsOwner()) {
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
     * @param $id
     * @return bool
     */
    public function redirectPath($id)
    {
        if (!Auth::guest()) {
            return route('future-letters.edit', $id);
        }
        return route('future-letters.index');
    }
}
