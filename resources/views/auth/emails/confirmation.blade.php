Click here to confirm your account: <a href="{{ $link = url('do-confirmation', \App\ConfirmationCode::generateConfirmationCode($user->id)) }}"> {{ $link }} </a>
