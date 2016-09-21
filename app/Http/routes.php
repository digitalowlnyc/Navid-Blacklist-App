<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Blacklist;
use App\ConfirmationCode;
use Illuminate\Support\Facades\Input;
use App\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user-confirmation', function () {
    return view('email-confirmation');
});

Route::get('/do-confirmation/{confirmationCode}', function ($confirmationCode) {
    $confirmationQueryRes = ConfirmationCode::where("confirmation_code", "=", $confirmationCode)->get();

    if(count($confirmationQueryRes) === 0) {
        $data = [
            "type" => "error_message",
            "heading" => "Error activating account",
            "message" => "Invalid code or bad link used"
        ];
        return view("message")->with("data", $data);
    }

    $confirmationRecord = $confirmationQueryRes[0];

    if($confirmationRecord->expired == 1) {
        $data = [
            "type" => "error_message",
            "heading" => "Error activating account",
            "message" => "The link has expired or been used already"
        ];
        return view("message")->with("data", $data);
    }

    $user = User::find($confirmationRecord->user_id);
    $user->is_confirmed = 1;
    $user->save();

    $confirmationRecord->expire();

    $data = [
        "type" => "ok_message",
        "heading" => "Account activated!",
        "message" => "You have successfully activated your account!"
    ];
    return view("message")->with("data", $data);
});


Route::post('/send-confirmation', function () {

    if(!checkCaptcha(Input::get("g-recaptcha-response"))) {
        $validator = Validator::make([], []);
        $validator->errors()->add('g-captcha-input', 'Captcha response invalid');
        return redirect("user-confirmation")->withErrors($validator);
    }

    Mail::send('auth.emails.confirmation', [], function($message) {
        $message->to(Auth::user()->email);
        $message->subject('Blacklist: Please confirm your email');
    });

    return view('confirmation-sent');
});

Route::get('/blacklist-search', function () {
    return view("search")->with("results", null);
});

function checkCaptcha($googleCaptchaResponse) {
    $API_SECRET = "6Ld8VCkTAAAAAO-C5_tEzIT2FvI3-dPZqfoMUsug";
    $API_URL = "https://www.google.com/recaptcha/api/siteverify";

    $data = array('secret' => $API_SECRET, 'response' => $googleCaptchaResponse);

    $handle = curl_init($API_URL);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, True);

    $response = curl_exec($handle);

    if(!$response) {
        die("Failed calling Google API");
    }

    $responseArray = json_decode($response, true);

    return $responseArray["success"] === True;
}

Route::post('/blacklist-search', function (Request $request) {

    $accountId = Input::get('account-id');

    if(!checkCaptcha(Input::get("g-recaptcha-response"))) {
        $validator = Validator::make([], []);
        $validator->errors()->add('g-captcha-input', 'Captcha response invalid');
        return redirect("blacklist-search")->withErrors($validator);
    }

    $entries = DB::table('blacklist')
        ->select('account_id', DB::raw('count(*) as count_account_id'))
        ->where("account_id", "=", $accountId)->get();

    $filtered = [];
    foreach($entries as $entry) {
        if($entry->count_account_id < 2) {
            continue;
        }
        $filtered[] = $entry;
    }

    return view("search")->with("results", $filtered);
});

Route::group(['middleware' => ['auth', "confirmed-user"]], function () {

    Route::get('/blacklist-submit', function () {
        return view("blacklist");
    });

    Route::get('/blacklist-view', function () {
        $entries = DB::table('blacklist')
            ->select('account_id', DB::raw('count(*) as count_account_id'))
            ->groupBy('account_id')->get();

        $filtered = [];
        foreach($entries as $entry) {
            if($entry->count_account_id < 2) {
                continue;
            }
            $filtered[] = $entry;
        }

        return view("show-blacklist")->with("blacklist", $filtered);
    });

    Route::post('/blacklist-rest', function (Request $request) {

        $validator = Validator::make([], []);

        $accountId = Input::get('account-id');

        $captchaValid = checkCaptcha(Input::get("g-recaptcha-response"));

        if(!$captchaValid) {
            $validator->errors()->add('g-captcha-input', 'Captcha response invalid');
            return redirect("blacklist-submit")->withErrors($validator);
        }

        Blacklist::create([
            "entered_by" => Auth::user()->id,
            "account_id" => $accountId,
        ]);

        return redirect("/blacklist-view");
    });
});

Route::auth();

Route::get('/home', 'HomeController@index');
