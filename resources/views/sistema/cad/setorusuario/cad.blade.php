@extends('layouts.app')

@section('content')
    <header class="page-header header-no-margin">
        <h1>{{ $titulo }}</h1>
    </header>
    @if ($modo == 'create')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/setorusuario') }}">
        {!! csrf_field() !!}
    @else
    {{ Form::model($setorusuario, array('route' => array('setorusuario.update', $setorusuario->id), 'class' => 'form-horizontal')) }}
        <input type="hidden" name="_method" value="PUT">
    @endif
        <div class="form-group{{ $errors->has('id_usuario') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('setorusuario-cad.usuario') }}</label>
            <div class="col-md-10">
                {{ Form::select('id_usuario', $usuarios, old('id_usuario'), ['class' => 'form-control']) }}
                @if ($errors->has('id_usuario'))
                    <span class="help-block">
                        <strong>{{ $errors->first('id_usuario') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('id_setor') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('setorusuario-cad.setor') }}</label>
            <div class="col-md-10">
                {{ Form::select('id_setor', $setores, old('id_setor'), ['class' => 'form-control']) }}
                @if ($errors->has('id_setor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('id_setor') }}</strong>
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
    <script type="text/javascript">
        function hideButton() {
            $('#btn-cadastrar').hide();
            $('#mensagem').text('Efetuando cadastro! Aguarde!');
        }
    </script>
@endsection
