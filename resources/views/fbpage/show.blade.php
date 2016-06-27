@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $fbpage->name }}</div>
                    <div class="panel-body">
                        {{-- Menü Tabs --}}
                        <ul class="nav nav-tabs nav-justified">
                            <li role="presentation" class="active"><a href="#">Posts</a></li>
                            <li role="presentation"><a href="{{ url(niceEncode($fbpage->name) . '/analyse') }}">Analyse</a></li>
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
                            <a href="{{ url(niceEncode($fbpage->name) . '/nachladen') }}">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Posts nachladen
                                </button>
                            </a>
                            @if ($fbpage->analyzing)
                                <a id="stop-analysis" href="{{ url(niceEncode($fbpage->name) . '/analyse/stop') }}">
                                    <button type="button" class="btn btn-default">
                                        <i class="fa fa-stop"></i> Nutzeranalyse stoppen
                                    </button>
                                </a>
                            @else
                                <a id="start-analysis" href="{{ url(niceEncode($fbpage->name) . '/analyse/start') }}">
                                    <button type="button" class="btn btn-default">
                                        <i class="fa fa-play" aria-hidden="true"></i> Nutzeranalyse starten
                                    </button>
                                </a>
                            @endif
                        </div>

                        {{-- Posts Tabelle --}}
                        <table id="posts" class="table table-hover">
                            <tr>
                                <th>Post</th>
                                <th>Veröffentlicht</th>
                                <th>Nutzerdaten</th>
                                <th>Aktion</th>
                            </tr>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->text }}</td>
                                    <td>{{ $post->published_at }}</td>
                                    <td>{{ count($post->users()->get()) }}</td>
                                    <td>
                                        <a href="{{ url(niceEncode($fbpage->name) . '/' . $post->id . '/markieren') }}" title="Markierte Personen hinzufügen">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        <a href="http://facebook.com/{{ $post->facebook_id }}" target="_blank" title="Auf Facebook öffnen">
                                            <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        {{-- Pagination --}}
                        <div class="center">
                            {!! $posts->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
