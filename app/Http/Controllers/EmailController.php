<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * Date: 9/21/16
 * Time: 9:43 AM
 * License: (All rights reserved)
 */
class EmailController extends Controller {
    public static function sendConfirmationEmail($user) {
        Mail::send('auth.emails.confirmation', ["user" => $user], function($message) use($user) {
            $email = $user->email;
            $message->to($email);
            $message->subject('Blacklist: Please confirm your email');
        });
    }
}