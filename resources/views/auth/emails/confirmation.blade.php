Click here to confirm your account: <a href="{{ $link = url('do-confirmation', \App\ConfirmationCode::generateConfirmationCode(Auth::user()->id)) }}"> {{ $link }} </a>
