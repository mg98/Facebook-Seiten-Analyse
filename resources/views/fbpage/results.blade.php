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
                            <li role="presentation"><a href="{{ url(nice_encode($fbpage->name)) }}">Posts</a></li>
                            <li role="presentation" class="active"><a href="#">Analyse</a></li>
                        </ul>

                        {{-- @include('pagination', ['paginator' => $user_pagination, 'amount_of_pages' => $amount_of_pages]) --}}

                        {{-- Posts Tabelle --}}
                        <table class="table table-hover users">
                            <tr>
                                <th>Name</th>
                                <th>Aktivitäten</th>
                                <th>Getrackt sein</th>
                                <th>Zuletzt getrackt</th>
                                <th>Aktion</th>
                            </tr>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->count }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->updated_at }}</td>
                                    <td>
                                        <a href="http://facebook.com/{{ $user->facebook_id }}" target="_blank" title="Auf Facebook öffnen">
                                            <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        {{-- Pagination --}}
                        <div class="center">
                            {{ $pagination->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
