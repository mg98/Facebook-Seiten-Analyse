@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $fbpage->name }}</div>
                    <div class="panel-body">
                        <a href="{{ niceEncode($fbpage->name) }}/getposts">Posts nachladen</a>
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
                                        <a href="http://facebook.com/{{ $post->facebook_id }}" target="_blank">
                                            <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ $post->id }}/delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
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
