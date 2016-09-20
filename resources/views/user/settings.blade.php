@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-cog"></i> Einstellungen</div>
                    <div class="panel-body">
                        <h3>Facebook API</h3>
                        {!! Form::open(['method' => 'POST']) !!}
                            <div class="form-group">
                                <label for="appId">App ID</label>
                                {!! Form::text('fb_app_id', Auth::user()->fb_app_id, [
                                                    'required' => 'required',
                                                    'maxlength' => 16,
                                                    'class' => 'form-control',
                                                    'id' => 'appId'
                                                ]) !!}
                            </div>
                            <div class="form-group">
                                <label for="appSecret">App Secret</label>
                                {!! Form::text('fb_app_secret', Auth::user()->fb_app_secret, [
                                                    'required' => 'required',
                                                    'maxlength' => 32,
                                                    'class' => 'form-control',
                                                    'id' => 'appSecret'
                                                ]) !!}

                            </div>
                            <div class="form-group">
                                <label for="accesstoken">Accesstoken</label>
                                {!! Form::textarea('fb_accesstoken', Auth::user()->fb_accesstoken, [
                                                    'required' => 'required',
                                                    'maxlength' => 256,
                                                    'rows' => 3,
                                                    'class' => 'form-control',
                                                    'id' => 'accesstoken'
                                                ]) !!}

                            </div>

                            {!! Form::submit('Speichern', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
