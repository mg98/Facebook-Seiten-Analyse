@extends('layouts.app')

@section('content')
    @inject('uac', '\App\Http\Controllers\UserAnalysisController')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">

                        <div id="fbpages">
                            @forelse (collection_chunk($fbpages, 3) as $fbpages)
                                <div class="row">
                                    @foreach ($fbpages as $fbpage)
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <a href="http://facebook.com/{{ $fbpage->facebook_id }}" target="_blank" title="Auf Facebook öffnen">
                                                        <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                                    </a>
                                                    <a href="{{ url(nice_encode($fbpage->name)) }}">
                                                        {{ $fbpage->name }}
                                                    </a>

                                                    {!! Form::open(['method' => 'POST', 'class' => 'pull-right', 'url' => nice_encode($fbpage->name) . '/delete']) !!}
                                                        {!! Form::hidden('id', $fbpage->id) !!}
                                                        <i class="fa fa-close fbpage-delete" id="{{ $fbpage->id }}" name="{{ $fbpage->name }}" title="Löschen"></i>
                                                    {!! Form::close() !!}
                                                </div>
                                                <div class="panel-body">
                                                    <table>
                                                        <tr>
                                                            <td>Hinzugefügt am:</td>
                                                            <td>{{ $fbpage->created_at->format('d.m.Y') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Posts:</td>
                                                            <td>{{ $fbpage->posts()->count() }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nutzerdaten:</td>
                                                            <td>{{ $fbpage->users()->count() }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Zuletzt analysiert:</td>
                                                            <td>
                                                                @if ($fbpage->analyzing)
                                                                    <i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw margin-bottom" aria-hidden="true"></i> Analysiere
                                                                @else
                                                                    <?php $lastAnalysis = $uac::getLastAnalysis($fbpage) ?>
                                                                    @if ($lastAnalysis)
                                                                        <span title="{{ $lastAnalysis->format('d.m.Y H:i:s') }}">
                                                                            <i class="fa fa-check" aria-hidden="true"></i> {{ $lastAnalysis->diffForHumans() }}
                                                                        </span>

                                                                    @else
                                                                        Noch nie analysiert
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @empty
                                <p>Sie haben noch keine Facebook Seiten angelegt.</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
