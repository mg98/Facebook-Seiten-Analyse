@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Facebook Seite hinzufügen</div>

                    <div class="panel-body">

                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach

                        {!! Form::open(['method' => 'POST']) !!}
                            <p>http://facebook.com/
                                {!! Form::text('page', null, ['placeholder' => 'Seiten-ID', 'required' => 'required']) !!}
                            </p>
                            {!! Form::submit('Hinzufügen', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
