@extends('layouts.fbpage', ['nav' => 'posts'])

@section('table')
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
                <td>{!! $post->text !!}</td>
                <td>{{ $post->published_at }}</td>
                <td>{{ count($post->users()->get()) }}</td>
                <td>
                    <a href="{{ url(nice_encode($fbpage->name) . '/' . $post->id . '/markieren') }}" title="Markierte Personen hinzufügen">
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
@endsection
