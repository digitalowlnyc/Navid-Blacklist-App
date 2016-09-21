@extends('layouts.app')
<script src='https://www.google.com/recaptcha/api.js'></script>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Email Confirmation</div>
                    <div class="panel-body">
                        <div class="lead">You must first confirm your email before logging in. Please use the form below if you need to request a confirmation email again.</div>

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/send-confirmation') }}">
                            {{ csrf_field() }}

                            <!--div class="form-group{{ $errors->has('account-id') ? ' has-error' : '' }}">
                                <label for="account-id" class="col-md-4 control-label">IBAN</label>

                                <div class="col-md-6">
                                    <input id="account-id" type="text" class="form-control" name="account-id" value="{{ old('account-id') }}">

                                    @if ($errors->has('account-id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('account-id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div-->


                            <div class="form-group{{ $errors->has('g-captcha-input') ? ' has-error' : '' }}">
                                <label for="account-id" class="col-md-4 control-label">Captcha</label>

                                <div class="col-md-6">
                                    <div id="g-captcha-input" name="g-captcha-input" class="g-recaptcha" data-sitekey="6Ld8VCkTAAAAAAFCoSPBY4rkYMZXNX1hHOE-tt0w"></div>

                                    @if ($errors->has('g-captcha-input'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('g-captcha-input') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-envelope-o"></i> Send Confirmation Email
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
