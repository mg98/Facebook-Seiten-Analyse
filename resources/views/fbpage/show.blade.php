@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $fbpage->name }}</div>
                    <div class="panel-body">

                        <ul class="nav nav-tabs nav-justified">
                            <li role="presentation" class="active"><a href="#">Posts</a></li>
                            <li role="presentation"><a href="analyse">Analyse</a></li>
                        </ul>

                        <div id="action-bar">
                            <a href="{{ niceEncode($fbpage->name) }}/getposts">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Posts nachladen
                                </button>
                            </a>
                        </div>


                        <table class="table table-hover">
                            <tr>
                                <th>Post</th>
                                <th>Veröffentlicht</th>
                                <th>Likes</th>
                                <th>Kommentare</th>
                                <th>Aktion</th>
                            </tr>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->text }}</td>
                                    <td>{{ $post->published_at }}</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>
                                        <a href="http://facebook.com/{{ $post->facebook_id }}" target="_blank" title="Auf Facebook öffnen">
                                            <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="center">
                            {!! $posts->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
