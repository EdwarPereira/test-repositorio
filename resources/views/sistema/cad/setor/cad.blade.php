@extends('layouts.app')

@section('content')
    <header class="page-header header-no-margin">
        <h1>{{ $titulo }}</h1>
    </header>
    @if ($modo == 'create')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/setor') }}">
        {!! csrf_field() !!}
    @else
        {{ Form::model($setor, array('route' => array('setor.update', $setor->id_setor), 'class' => 'form-horizontal')) }}
        <input type="hidden" name="_method" value="PUT">
    @endif
    <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">{{ trans('setor-cad.nome') }}</label>
        <div class="col-md-10">
            {{ Form::text('nome', old('nome'), array('class' => 'form-control')) }}
            @if ($errors->has('nome'))
                <span class="help-block">
                    <strong>{{ $errors->first('nome') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('responsavel') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">{{ trans('setor-cad.responsavel') }}</label>
        <div class="col-md-10">
            {{ Form::text('responsavel', old('responsavel'), array('class' => 'form-control')) }}
            @if ($errors->has('responsavel'))
                <span class="help-block">
                    <strong>{{ $errors->first('responsavel') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">{{trans('setor-cad.email')}}</label>
        <div class="col-md-10">
            {{ Form::text('email', old('email'), array('class' => 'form-control')) }}
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('tempo') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">{{ trans('setor-cad.tempo') }}</label>
        <div class="col-md-10">
            {{ Form::text('tempo', old('tempo'), array('class' => 'form-control')) }}
            @if ($errors->has('tempo'))
                <span class="help-block">
                    <strong>{{ $errors->first('tempo') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">{{ trans('setor-cad.status') }}</label>
        <div class="col-md-10">
            {{ Form::select('status', ['Inativo', 'Ativo'],  old('status'),['class' => 'form-control']) }}
            @if ($errors->has('status'))
                <span class="help-block">
                    <strong>{{ $errors->first('status') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-10 col-md-offset-2">
            <button type="submit" class="btn btn-primary" onclick="hideButton()" id="btn-cadastrar">
                <i class="fa fa-btn fa-user"></i>{{ trans('setor-cad.cadastrar') }}
            </button>
        </div>
    </div>
    <div id="mensagem" align="center" class="blink"> </div>
    {{ Form::close() }}
    </div>
    <script type="text/javascript">
        function hideButton() {
            $('#btn-cadastrar').hide();
            $('#mensagem').text('Efetuando cadastro de setor! Aguarde!');
        }
    </script>
@endsection
