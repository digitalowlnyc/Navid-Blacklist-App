@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    @if(!Auth::check())
                        Please register/login to use the tool.
                    @else
                        Hello {{Auth::user()->email}}! Welcome.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
