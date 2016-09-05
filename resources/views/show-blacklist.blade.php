@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Blacklist View</div>

                    <div class="panel-body">
                        <table class="table">
                            <tr>
                               <td>IBAN</td>
                               <td># Times Submitted</td>
                            </tr>
                            @foreach($blacklist as $entry)
                                <tr><td>{{$entry->account_id}}</td><td>{{$entry->count_account_id}}</td></tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
