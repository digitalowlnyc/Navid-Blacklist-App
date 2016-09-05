@extends('layouts.app')
<script src='https://www.google.com/recaptcha/api.js'></script>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Search Blacklist</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/blacklist-search') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('account-id') ? ' has-error' : '' }}">
                                <label for="account-id" class="col-md-4 control-label">IBAN</label>

                                <div class="col-md-6">
                                    <input id="account-id" type="text" class="form-control" name="account-id" value="{{ old('account-id') }}">

                                    @if ($errors->has('account-id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('account-id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <!--div class="form-group{{ $errors->has('g-captcha-input') ? ' has-error' : '' }}">
                                <label for="account-id" class="col-md-4 control-label">Captcha</label>

                                <div class="col-md-6">
                                    <div id="g-captcha-input" name="g-captcha-input" class="g-recaptcha" data-sitekey="6Ld8VCkTAAAAAAFCoSPBY4rkYMZXNX1hHOE-tt0w"></div>

                                    @if ($errors->has('g-captcha-input'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('g-captcha-input') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div-->

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td>IBAN</td>
                                <td># Times Submitted</td>
                            </tr>
                            @if(isset($results))
                            @if(count($results) > 0)
                                @foreach($results as $entry)
                                    <tr><td>{{$entry->account_id}}</td><td>{{$entry->count_account_id}}</td></tr>
                                @endforeach
                            @else
                                No results
                            @endif
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
