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
                        </table>
                        <nav>
                            <ul class="pager">
                                <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Älter</a></li>
                                <li class="next"><a href="#">Neuer <span aria-hidden="true">&rarr;</span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
