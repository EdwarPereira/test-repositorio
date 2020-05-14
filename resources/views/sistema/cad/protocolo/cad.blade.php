@extends('layouts.app')

@section('content')
    <header class="page-header" style="margin-top: 0;">
        <h1 style="margin-top: 0;">{{ $titulo }}</h1>
    </header>
    @if ($modo == 'create')
    {{ Form::open(array('url' => '/protocolo', 'class' => 'form-horizontal', 'files' => true)) }}
    @else
    {{ Form::model($protocolo, array('route' => array('protocolo.update', $protocolo->id_protocolo), 'class' => 'form-horizontal', 'files' => 'true')) }}
        <input type="hidden" name="_method" value="PUT" />
    @endif
        <input type="hidden" name="origem" value="{{$origem}}" />
        <!-- Customer -->
        <div class="form-group{{ $errors->has('id_cliente') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.id_cliente') }}</label>
            <div class="col-md-10">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="{{ trans('protocolo-cad.select_customer') }}..." readonly="true" id="nomecliente" name="nomecliente" value="{{ old('nomecliente') }}" />
                    <span class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="modal" data-target="#myModal">{{ trans('protocolo-cad.search') }}</button></span>
                </div>
            </div>
        </div>
        <input type="hidden" id="id_cliente" name="id_cliente" value="{{ old('id_cliente') }}" />
        <!-- Modal Customer -->
        <div class="modal fade" id="myModal" tabindex="0" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Selecionar Cliente</h4>
                    </div>
                    <div class="modal-body">
                        <iframe src="/cliente" width="99%" height="400" frameborder="no" allowtransparency="true"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('messages.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Vehicle -->
        <div class="form-group{{ $errors->has('placa') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.placa') }}</label>
            <div class="col-md-10">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="{{ trans('protocolo-cad.select_plate') }}..."  id="placa" name="placa" value="{{ old('placa') }}" readonly="readonly" />
                    <span class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="modal" data-target="#myModalPlaca">{{ trans('protocolo-cad.search') }}</button></span>
                </div>
            </div>
        </div>
        <!-- Modal Vehicle -->
        <div class="modal fade" id="myModalPlaca" tabindex="0" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">{{ trans('protocolo-cad.select_plate_short') }}</h4>
                    </div>
                    <div class="modal-body">
                        <iframe src="/placa{{ old('id_cliente') != null ? '/' . old('id_cliente') : '' }}" width="99%" height="400" frameborder="0" allowtransparency="true" id="iframePlaca"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('messages.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_veiculo" id="id_veiculo">
        <input type="hidden" id="id_contato" name="id_contato" value="{{old('id_contato')}}">
        <!-- E-mail Contacts -->
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.email') }}</label>
            <div class="col-md-10">
                <div id="email" class="checkbox">
                    @forelse($emails as $email)
                        <label>
                            <input type="checkbox" value="{{ $email }}" name="email[]" @if(in_array($email, $old_email)) checked="checked" @endif />
                            {{ $email }}
                        </label>
                        <br />
                    @empty
                        {{ trans('protocolo-cad.select_client') }}
                    @endforelse
                </div>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <input type="hidden" name="id_usuario_cad" value={{ $usuario_logado }}>
        <!-- Sector -->
        @if ($id_setor == 0)
        <div class="form-group{{ $errors->has('id_setor') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.id_setor') }}</label>
            <div class="col-md-10">
                {{ Form::select('id_setor', $setores,  old('id_setor'), ['class' => 'form-control']) }}
                @if ($errors->has('id_setor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('id_setor') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        @else
        <div class="form-group{{ $errors->has('id_setor') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.id_setor') }}</label>
            <div class="col-md-10">
                {{ Form::select('id_setor', $setores,  $id_setor,['class' => 'form-control']) }}
                @if ($errors->has('id_setor'))
                    <span class="help-block">
                        <strong>{{ $errors->first('id_setor') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        @endif
        <!-- Requester -->
        <div class="form-group{{ $errors->has('solicitante') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.solicitante') }}</label>
            <div class="col-md-10">
                <input type="text" class="form-control"   id="solicitante" name="solicitante" value="{{old('solicitante')}}" />
                @if ($errors->has('solicitante'))
                    <span class="help-block">
                        <strong>{{ $errors->first('solicitante') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <!-- Modal Contato -->
        <div class="modal fade" id="myModalContato" tabindex="0" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Selecionar Contato</h4>
                    </div>
                    <div class="modal-body">
                        <iframe src="/contato" width="99%" height="400" frameborder="0" allowtransparency="true" id="iframeContato"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('messages.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Request -->
        <div class="form-group{{ $errors->has('solicitacao') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.solicitacao' )}}</label>
            <div class="col-md-10">
                {{ Form::textarea('solicitacao', old('solicitacao'), array('class' => 'form-control')) }}
                @if ($errors->has('solicitacao'))
                    <span class="help-block">
                        <strong>{{ $errors->first('solicitacao') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <!-- File -->
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
            <label class="col-md-2 control-label">{{ trans('protocolo-cad.arquivo' )}}</label>
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
        <!-- Submit -->
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="submit" class="btn btn-primary" onclick="hideButton()" id="btn-cadastrar">
                    <i class="fa fa-btn fa-user"></i>{{ trans('protocolo-cad.cadastrar') }}
                </button>
                <a href="{{ route('protocolo.index') }}" class="btn btn-default">{{ trans('messages.cancelar') }}</a>
            </div>
        </div>
        <!-- Loading Message -->
        <div id="mensagem" align="center" class="blink"></div>
    {{ Form::close() }}
    <script type="text/javascript">
        function hideButton() {
            $('#btn-cadastrar').hide();
            $('#mensagem').text('Efetuando cadastro! Aguarde!');
        }

        function loadClient(id, nome, email) {
            $('#nomecliente').val(nome);
            $('#id_cliente').val(id);

            $('#email').empty();

            $.getJSON("/contato/" + id, function(jsonData) {
                $.each(jsonData, function(i, data) {
                    $("#email").append(
                        $("<label>").text(' ' + data.email).prepend(
                            $("<input>").attr('type', 'checkbox').val(data.email)
                                .attr('name', 'email[]')
                        )
                    );
                    $("#email").append("<br>");
                });
            });

            $('#iframePlaca').attr('src', '/placa/' + id );
            $('#iframeContato').attr('src', '/contato/' + id );
            $('#myModal').modal('hide');
        }

        function loadPlaca(id, placa, cliente, clienteNome, clienteEmail) {
            $('#placa').val(placa);
            $('#id_veiculo').val(id);
            $('#nomecliente').val(clienteNome);

            $('#email').empty();

            $.getJSON("/contato/" + cliente, function(jsonData) {
                if (jsonData != null && jsonData.length > 0) {
                    $.each(jsonData, function (i, data) {
                        $("#email").append(
                            $("<label>").text(' ' + data.email).prepend(
                                $("<input>").attr('type', 'checkbox').val(data.email)
                                    .attr('name', 'email[]')
                            )
                        );
                        $("#email").append("<br>")
                    });
                } else {
                    $('#placa').val('');
                    $('#id_veiculo').val('');
                    $('#nomecliente').val('');
                    window.alert("@lang('protocolo-cad.no_contact')");
                }
            });

            $('#id_cliente').val(cliente);

            $('#iframeContato').attr('src','/contato/' + cliente );
            $('#myModalPlaca').modal('hide');
        }

        function loadContato(id, nome, email) {
            $('#id_contato').val(id);
            $('#email').val(email);
            $('#nomeContato').val(nome);
            $('#myModalContato').modal('hide');
        }
    </script>
@endsection