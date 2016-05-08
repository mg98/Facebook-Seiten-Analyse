@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Facebook Seite hinzufügen</div>

                    <div class="panel-body">
                        {!! Form::open(['method' => 'POST']) !!}
                            <p>http://facebook.com/{!! Form::text('facebook_id', ['required' => 'required']) !!}</p>
                            {!! Form::submit('Hinzufügen', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
