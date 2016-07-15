@extends('layouts.fbpage', ['nav' => 'analyse'])

@section('table')
    {{-- Posts Tabelle --}}
    <table id="users" class="table table-hover">
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
@endsection
