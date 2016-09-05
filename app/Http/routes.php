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
use Illuminate\Support\Facades\Input;
use League\Flysystem\Exception;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {

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

    Route::get('/blacklist-search', function () {
       return view("search")->with("results", []);
    });

    Route::post('/blacklist-rest', function (Request $request) {

        $validator = Validator::make([], []);

        $accountId = Input::get('account-id');
        if(!Auth::check()) {
            throw new Exception("User not authenticated");
        }

        $API_SECRET = "6Ld8VCkTAAAAAO-C5_tEzIT2FvI3-dPZqfoMUsug";
        $API_URL = "https://www.google.com/recaptcha/api/siteverify";
        $googleCaptchaResponse = Input::get("g-recaptcha-response");

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

        if($responseArray["success"] === True) {
        } else {
            $validator->errors()->add('g-captcha-input', 'Captcha response invalid');
            return redirect("blacklist-submit")->withErrors($validator);
        }

        Blacklist::create([
            "entered_by" => Auth::user()->id,
            "account_id" => $accountId,
        ]);

        return redirect("/blacklist-view");
    });

    Route::post('/blacklist-search', function (Request $request) {

        $validator = Validator::make([], []);

        $accountId = Input::get('account-id');
        if(!Auth::check()) {
            throw new Exception("User not authenticated");
        }
/*
        $API_SECRET = "6Ld8VCkTAAAAAO-C5_tEzIT2FvI3-dPZqfoMUsug";
        $API_URL = "https://www.google.com/recaptcha/api/siteverify";
        $googleCaptchaResponse = Input::get("g-recaptcha-response");

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

        if($responseArray["success"] === True) {
        } else {
            $validator->errors()->add('g-captcha-input', 'Captcha response invalid');
            return redirect("blacklist-search")->withErrors($validator);
        }
*/
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
});

Route::auth();

Route::get('/home', 'HomeController@index');
