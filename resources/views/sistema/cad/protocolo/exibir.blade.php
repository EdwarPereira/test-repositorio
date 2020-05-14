@extends('layouts.app')

@section('content')

    <div class="panel">

        <div class="panel-heading"><b> {{ $titulo }} </b></div>

            <div class="panel panel-default">

                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        </thead>
                        @foreach ($protocolos as $protocolo)
                            <tr>
                                <td class="table-text"><div> {{trans('protocolo-exibe.codigo')}} </div></td>
                                <td class="table-text"><div> {{ $protocolo->protocolo }} </div></td>
                            </tr>
                            <tr>
                                <td class="table-text"><div> {{trans('protocolo-exibe.data')}} </div></td>
                                <td class="table-text"><div> {{ date('d-m-Y H:i:s',strtotime($protocolo->data)) }} </div></td>
                            </tr>
                            <tr>
                                <td class="table-text"><div> {{trans('protocolo-exibe.cliente')}} </div></td>
                                <td class="table-text"><div> {{ $protocolo->name }} </div></td>
                            </tr>
                            <tr>
                                <td class="table-text"><div> {{trans('protocolo-exibe.setor')}} </div></td>
                                <td class="table-text"><div> {{ $protocolo->nome }} </div></td>
                            </tr>
                            <tr>
                                <td class="table-text"><div> {{trans('protocolo-exibe.solicitante')}} </div></td>
                                <td class="table-text"><div> {{ $protocolo->solicitante }} </div></td>
                            </tr>
                            <tr>
                                <td class="table-text"><div> {{trans('protocolo-exibe.solicitacao')}} </div></td>
                                <td class="table-text"><div> {{ $protocolo->solicitacao }} </div></td>
                            </tr>
                            @if ($protocolo->arquivo_type != '')
                            <tr>
                                <td class="table-text"><div> {{trans('protocolo-exibe.arquivo')}} </div></td>
                                <td class="table-text"><div> <img src={{ 'data:'.$protocolo->arquivo_type.';base64,'.$protocolo->arquivo }}  height="330" /> </div></td>
                            </tr>
                            @endif
                        @endforeach
                        <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tfoot>
                    </table>
                    <div align="center"> <a href="{{ url('/protocolo') }}"><i class="fa fa-btn fa-sign-out"></i>{{trans('protocolo-exibe.voltar')}}</a> </div>

                </div>
            </div>
        </div>

    </div>

@endsection

