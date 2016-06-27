@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Facebook Seiten markieren</div>
                    <div class="panel-body">
                        <h5><b><a href="{{ url($fbpage->name) }}">{{ $fbpage->name }}</a>:</b> {{ $post->text }}</h5>
                        <hr>

                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach

                        @if (Session::has('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <p class="text-info">Fügen Sie eine Facebook-Seite hinzu um Personen, welche diese Seite geliket
                            haben, bei der nalyse zu überspringen und nicht mit in die Datenbank aufzunehmen. Bedenken
                            Sie, dass bereits bekannte Facebook-Nutzer dadurch nicht automatisch gelöscht werden.</p>

                        {!! Form::open(['method' => 'POST']) !!}
                            <p>http://facebook.com/
                                {!! Form::text('page', null, ['placeholder' => 'Seiten-ID', 'required' => 'required']) !!}
                            </p>
                            {!! Form::submit('Hinzufügen', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}

                        <hr>

                        <p>
                            <b>Markiert: </b>
                            <i>Bisher keine</i>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
