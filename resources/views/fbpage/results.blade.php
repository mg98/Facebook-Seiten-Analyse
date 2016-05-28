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

                        {{-- Posts Tabelle --}}
                        <table class="table table-hover posts">
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
                                    <td>{{ count($post->getUsers()->get()) }}</td>
                                    <td>
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
