@extends('layouts.app')

@section('content')
    <header class="page-header header-no-margin">
        <h1>{{ $titulo }}</h1>
    </header>
    {{ Form::open(array('url' => '/relatorio', 'class' => 'form-horizontal', 'files' => true)) }}
        <input name="exibir" type="hidden" value="1" />
        <div class="form-group{{ ($errors->has('datainicio') || $errors->has('datafim')) ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('relatorio-listagem.periodo') }}</label>
            <div class="col-md-10">
                <div class="input-group input-daterange">
                    {{ Form::text('datainicio', old('datainicio'), array('class' => 'form-control','id' => 'datepicker', 'autocomplete' => 'off')) }}
                    <span class="input-group-addon">{{ trans('relatorio-listagem.ate') }}</span>
                    {{ Form::text('datafim', old('datafim'), array('class' => 'form-control','id' => 'datepicker2', 'autocomplete' => 'off')) }}
                </div>
                @if ($errors->has('datainicio'))
                    <span class="help-block">
                        <strong>{{ $errors->first('datainicio') }}</strong>
                    </span>
                @endif
                @if ($errors->has('datafim'))
                    <span class="help-block">
                        <strong>{{ $errors->first('datafim') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('ordenarpor') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('relatorio-listagem.ordenarpor') }}</label>
            <div class="col-md-10">
                {{ Form::select('ordenarpor', ['Protocolo', 'Status', 'Setor', 'Cliente'], old('ordenarpor'), ['class' => 'form-control']) }}
                @if ($errors->has('ordenarpor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('ordenarpor') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn fa-user"></i>{{ trans('relatorio-listagem.exibir') }}
                </button>
            </div>
        </div>
    {{ Form::close() }}
@endsection
