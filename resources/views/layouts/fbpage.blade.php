@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="http://facebook.com/{{ $fbpage->facebook_id }}" target="_blank" title="Auf Facebook öffnen">
                            <i class="fa fa-facebook-square" aria-hidden="true"></i>
                        </a>
                        {{ $fbpage->name }}
                    </div>
                    <div class="panel-body">
                        {{-- Menü Tabs --}}
                        <ul class="nav nav-tabs nav-justified">
                            @if ($nav == 'posts')
                                <li role="presentation" class="active"><a href="#">Posts</a></li>
                                <li role="presentation"><a href="{{ url(nice_encode($fbpage->name) . '/analyse') }}">Analyse</a></li>
                            @elseif ($nav== 'analyse')
                                <li role="presentation"><a href="{{ url(nice_encode($fbpage->name)) }}">Posts</a></li>
                                <li role="presentation" class="active"><a href="#">Analyse</a></li>
                            @endif
                        </ul>

                        {{-- Alerts --}}
                        <div id="alerts">
                            <div class="alert alert-info">
                                <i class="glyphicon glyphicon-info-sign"></i> Die Analyse wurde gestartet. Dies kann einige Minuten in Anspruch nehmen.
                            </div>
                            @if (Session::has('success'))
                                <div class="alert alert-success">
                                    <i class="glyphicon glyphicon-ok-sign"></i> Die Analyse wurde erfolgreich durchgeführt!
                                </div>
                            @endif
                            @if (Session::has('failure'))
                                <div class="alert alert-danger">
                                    <i class="glyphicon glyphicon-remove-sign"></i> Die Facebook SDK hat einen Fehler geworfen: {!! session('failure') !!}
                                </div>
                            @endif
                        </div>

                        {{-- Steuerelemente --}}
                        <div id="action-bar">
                            <a href="{{ url(nice_encode($fbpage->name) . '/nachladen') }}">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Posts nachladen
                                </button>
                            </a>
                            @if ($fbpage->analyzing)
                                <a id="stop-analysis" href="{{ url(nice_encode($fbpage->name) . '/analyse/stop') }}">
                                    <button type="button" class="btn btn-default">
                                        <i class="fa fa-stop"></i> Nutzeranalyse stoppen
                                    </button>
                                </a>
                            @else
                                <a id="start-analysis" href="{{ url(nice_encode($fbpage->name) . '/analyse/start') }}">
                                    <button type="button" class="btn btn-default">
                                        <i class="fa fa-play" aria-hidden="true"></i> Nutzeranalyse starten
                                    </button>
                                </a>
                            @endif
                            <a href="{{ url(nice_encode($fbpage->name) . '/export') }}">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-cloud-download" aria-hidden="true"></i> CSV Export
                                </button>
                            </a>
                            <a id="page-reset" href="{{ url(nice_encode($fbpage->name) . '/reset') }}">
                                <button type="button" class="btn btn-danger">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Hard Reset
                                </button>
                            </a>
                            <a id="analysis-reset" href="{{ url(nice_encode($fbpage->name) . '/analyse/reset') }}">
                                <button type="button" class="btn btn-danger">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Nutzerdaten zurücksetzen
                                </button>
                            </a>
                        </div>

                        @yield('table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
