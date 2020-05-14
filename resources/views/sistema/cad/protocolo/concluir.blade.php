@extends('layouts.app')

@section('content')
    <header class="page-header" style="margin-top: 0;">
        <h1 style="margin-top: 0;">{{ $titulo }}</h1>
    </header>
    @if ($modo == 'create')
    {{ Form::open(array('url' => '/protocolo/' . $id_protocolo . '/concluir', 'class' => 'form-horizontal', 'files' => true)) }}
        <input type="hidden" name="id_protocolo" value="{{ $id_protocolo }}">
    @else
    {{ Form::model($historico, array('route' => array('protocolo/' . $id_protocolo . '/concluir.update', $historico->id_historico),'files' => 'true', 'class' => 'form-horizontal')) }}
        <input type="hidden" name="_method" value="PUT">
    @endif
        <div class="form-group">
            <label class="col-md-2 control-label">{{ trans('protocolo-concluir.criacao') }}</label>
            <div class="col-md-10">
                {{ Form::text('data', date('d/m/Y H:i:s', strToTime($data)), array('class' => 'form-control','readonly' => 'true')) }}
            </div>
        </div>
        <input type="hidden" name="id_setor_original" value="{{ $id_setor }}" />
        <div class="form-group">
            <label class="col-md-2 control-label">{{ trans('protocolo-concluir.ultimainteracao') }}</label>
            <div class="col-md-10">
                {{ Form::text('data', date('d/m/Y H:i:s', strToTime($dum)), array('class' => 'form-control','readonly' => 'true')) }}
            </div>
        </div>
        <input type="hidden" name="id_usuario" value={{ $usuario_logado }} />
        <div class="form-group{{ $errors->has('concluido') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-concluir.concluido') }}</label>
            <div class="col-md-10">
                {{ Form::select('concluido', [trans('protocolo-concluir.nao'), trans('protocolo-concluir.simquero')],  old('concluido'), ['class' => 'form-control']) }}
                @if ($errors->has('concluido'))
                    <span class="help-block">
                        <strong>{{ $errors->first('concluido') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('id_setor') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-concluir.id_setor') }}</label>
            <div class="col-md-10">
                {{ Form::select('id_setor', $setores, $id_setor , ['class' => 'form-control']) }}
                @if ($errors->has('id_setor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('id_setor') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('previsao') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-concluir.previsao') }}</label>
            <div class="col-md-10">
                {{ Form::text('previsao', old('previsao'), array('class' => 'form-control', 'id' => 'datepicker',
                    'autocomplete' => 'off')) }}
                @if ($errors->has('previsao'))
                    <span class="help-block">
                        <strong>{{ $errors->first('previsao') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('observacao') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-concluir.observacao') }}</label>
            <div class="col-md-10">
                {{ Form::textarea('observacao', old('observacao'), array('class' => 'form-control')) }}
                @if ($errors->has('observacao'))
                    <span class="help-block">
                        <strong>{{ $errors->first('observacao') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        @php($hasFileError = false)
        @php($fileErrors = array())
        @if (count($errors) > 0)
            @php
                foreach ($errors->keys() as $key) {
                    if (strpos($key, 'arquivo') !== false) {
                        $hasFileError = true;
                        array_push($fileErrors, $errors->first($key));
                    }
                }
            @endphp
        @endif
        <div class="form-group{{ $hasFileError ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-concluir.arquivo') }}</label>
            <div class="col-md-10">
                <input type="file" name="arquivo[]" id="arquivo" multiple />
                @if ($hasFileError)
                    @foreach($fileErrors as $fileError)
                        <span class="help-block">
                        <strong>{{ $fileError }}</strong>
                    </span>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="submit" class="btn btn-primary" id="btn-cadastrar" onclick="window.parent.hideButton()">
                    <i class="fa fa-btn fa-user"></i>{{trans('protocolo-concluir.cadastrar')}}
                </button>
            </div>
            <div id="mensagem" align="center" class="blink"> </div>
        </div>
    {{ Form::close() }}

    <!-- MODAL CONFIRMA SETOR -->
    <div class="modal fade" tabindex="-1" role="dialog" id="myModalConfirmaSetor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{trans('protocolo-concluir.confirmartrocasetor') }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="abandonasetor">{{trans('protocolo-concluir.nao')}}</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="confirmasetor">{{trans('protocolo-concluir.simquero')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CONFIRMA CONCLUIR -->
    <div class="modal fade" tabindex="-1" role="dialog" id="myModalConfirmaConcluir">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{trans('protocolo-concluir.confirmarconcluir') }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="naoconcluir">{{ trans('protocolo-concluir.nao') }}</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="simconcluir">{{ trans('protocolo-concluir.simquero') }}</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var previousSelectSetor    = $('select[name="id_setor"]').val();
        var previousSelectConcluir = $('select[name="concluido"]').val();

        $('select[name="concluido"]').on('change', function(e) {
            $('#myModalConfirmaConcluir').modal();
        });

        $('select[name="id_setor"]').on('change', function(e) {
            $('#myModalConfirmaSetor').modal();
        });

        $('#abandonasetor').on('click', function(e) {
            $('select[name="id_setor"]').val(previousSelectSetor);
        });

        $('#confirmasetor').on('click', function(e) {
            previousSelectSetor = $('select[name="id_setor"]').find('option:selected').val();
        });

        $('#naoconcluir').on('click', function(e) {
            $('select[name="concluido"]').val(previousSelectConcluir);
        });

        $('#simconcluir').on('click', function(e) {
            previousSelectConcluir = $('select[name="concluido"]').find('option:selected').val();
        });

        function hideButton() {
            $('#btn-cadastrar').hide();
            $('#mensagem').text('Enviando e-mail! Aguarde!');
        }
    </script>
@endsection
