@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs" id="abas">
        @if (count($setoresusuario) == 1)
            @foreach ($setoresusuario as $setor)
                <li role="presentation"><a href="/protocolo/{{ $setor->id_setor }}/setor" >{{ $setor->nome }}</a></li>
            @endforeach
        @else
            @if ($aba == 0)
                <li role="presentation" class="active"><a href="/protocolo/?status={{ $filtrostatus }}">TODOS OS SETORES</a></li>
            @else
                <li role="presentation" ><a href="/protocolo/?status={{ $filtrostatus }}">TODOS OS SETORES</a></li>
            @endif
            @foreach ($setoresusuario as $setor)
                @if ($aba == $setor->id_setor)
                    <li role="presentation" class="active"><a href="/protocolo/{{ $setor->id_setor }}/setor/?status=pendentes">{{ $setor->nome }}</a></li>
                @else
                    <li role="presentation"><a href="/protocolo/{{ $setor->id_setor }}/setor/?status=pendentes" >{{ $setor->nome }}</a></li>
                @endif
            @endforeach
        @endif
    </ul>
    <div style="padding-top: 15px;">
        <small>{{ trans('protocolo-lista.ordenarpor') }}</small>
        <a href="{{ $filtrotodos }}" id="filtraTodos"><span class="label label-default">{{ trans('protocolo-lista.filtratodos') }}</span></a>
        <a href="{{ $filtropendente }}" id="filtraPendentes"><span class="label label-danger">{{ trans('protocolo-lista.filtrapendentes') }}</span></a>
        <a href="{{ $filtroconcluido }}" id="filtraConcluidos"><span class="label label-success">{{ trans('protocolo-lista.filtraconcluidos') }}</span></a>
        <hr style="margin-top: 10px;" />
    </div>
    <header class="page-header" style="margin-top: 5px;">
        <div class="clearfix">
            <h2 class="pull-left">{{ $titulo }}</h2>
            <a href="{{ url($url . '/create') }}" class="btn btn-primary pull-right" style="margin-top: 20px;"><span class="glyphicon glyphicon-plus"></span> {{ trans('search.novo') }}</a>
        </div>
    </header>
    @if (count($protocolos) > 0)
        {!! Form::open(['method' => 'GET','url' => $url, 'class' => 'form-horizontal', 'role'=>'search']) !!}
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="{{ trans('search.buscar' )}}..." value="{{ $search }}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th><a href="/protocolo/{{$filtrosetor}}?status={{$filtrostatus}}&ordem=id&modo={{$modo}}" id="ordenaid">{{trans('protocolo-lista.id')}}</a></th>
                    <th><a href="/protocolo/{{$filtrosetor}}?status={{$filtrostatus}}&ordem=data&modo={{$modo}}" id="ordenadata">{{trans('protocolo-lista.data')}}</a></th>
                    <th><a href="/protocolo/{{$filtrosetor}}?status={{$filtrostatus}}&ordem=cliente&modo={{$modo}}" id="ordenacliente">{{trans('protocolo-lista.cliente')}}</a></th>
                    <th><a href="/protocolo/{{$filtrosetor}}?status={{$filtrostatus}}&ordem=setor&modo={{$modo}}" id="ordenasetor">{{trans('protocolo-lista.setor')}}</a></th>
                    <th><a href="/protocolo/{{$filtrosetor}}?status={{$filtrostatus}}&ordem=status&modo={{$modo}}">{{trans('protocolo-lista.status')}}</a></th>
                    <th><a href="/protocolo/{{$filtrosetor}}?status={{$filtrostatus}}&ordem=previsao&modo={{$modo}}">{{trans('protocolo-lista.previsao')}}</a></th>
                    <th>{{trans('protocolo-lista.opcoes')}}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($protocolos as $protocolo)
                <tr>
                    <td class="table-text">{{ $protocolo->protocolo }}</td>
                    <td class="table-text">{{ date('d/m/Y', strtotime($protocolo->data)) }}</td>
                    <td class="table-text">{{ $protocolo->name }}</td>
                    <td class="table-text">{{ $protocolo->nome }}</td>
                    <td class="table-text">
                        <div>
                        @if ($protocolo->status == 0)
                            <!-- se estiver vencendo hoje colocar como amarelo -->
                            @if (date('d/m/Y', strToTime($protocolo->dum)) == date('d/m/Y', strToTime($now)))
                                <span class="label label-warning">
                            @else
                                <span class="label label-danger">
                            @endif
                            {{ trans('protocolo-lista.pendente') }}
                            </span>
                        @else
                            <span class="label label-success">
                                {{ trans('protocolo-lista.concluido') }}
                            </span>
                        @endif
                        </div>
                    </td>
                    <td class="table-text">
                        @if (strToTime($protocolo->dum) < strToTime($now))
                            @if ($protocolo->status == 0)
                                <div class="blink">
                            @endif
                        @else
                            <div>
                        @endif
                        {{  date('d/m/Y', strtotime($protocolo->dum)) }}
                        </div>
                    </td>
                    <td class="table-text">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default" onclick="window.location.href='{{ url('/protocolo/'.$protocolo->id_protocolo.'/timeline')}}'" >{{ trans('protocolo-lista.timeline') }}</button>
                            @if ($protocolo->status == 0)
                                <button type="button" class="btn btn-default" onclick="window.location.href='{{ url('/protocolo/'.$protocolo->id_protocolo.'/concluir/create') }}'" >{{ trans('protocolo-lista.concluir') }}</button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="6">
                        {!! $protocolos->links() !!}
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
        <div>
            <p class="text-center">{{ trans('protocolo-lista.naoexistenenhumprotocolo') }}</p>
        </div>
    @endif
@endsection