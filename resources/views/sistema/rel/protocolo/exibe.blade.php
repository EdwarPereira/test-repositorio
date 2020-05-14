@extends('layouts.app')

@section('content')
    <header class="page-header header-no-margin">
        <h1>{{ $titulo }}</h1>
    </header>
    @if (count($dados) > 0)
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th scope="col">{{ trans('relatorio-listagem.id') }}</th>
                    <th scope="col">{{ trans('relatorio-listagem.data') }}</th>
                    <th scope="col">{{ trans('relatorio-listagem.dum') }}</th>
                    <th scope="col">{{ trans('relatorio-listagem.cliente') }}</th>
                    <th scope="col">{{ trans('relatorio-listagem.setor') }}</th>
                    <th scope="col">{{ trans('relatorio-listagem.status') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($dados as $relatorio)
                <tr>
                    <td class="table-text">{{ $relatorio->protocolo }}</td>
                    <td class="table-text">{{ date('d/m/Y H:i:s', strtotime($relatorio->data)) }}</td>
                    <td class="table-text">{{ date('d/m/Y H:i:s',strtotime($relatorio->dum )) }}</td>
                    <td class="table-text">{{ $relatorio->name }}</td>
                    <td class="table-text">{{ $relatorio->nome }}</td>
                    <td class="table-text">
                        @if ($relatorio->status == 0)
                            <!-- se estiver vencendo hoje colocar como amarelo -->
                            @if (date('d/m/Y',strToTime($relatorio->data)) == date('d/m/Y',strToTime($now)))
                            <span class="label label-default">
                            @else
                            <span class="label label-danger">
                            @endif
                                {{  trans('relatorio-listagem.pendente')}}
                                </span>
                        @else
                            <span class="label label-success">
                                {{trans('relatorio-listagem.concluido')}}
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <br />
                        <p><strong>Pendentes:</strong> {{ $pendente }}</p>
                        <p><strong>Concluídos:</strong> {{ $concluido }}</p>
                        <p><strong>Total:</strong> {{$total}}</p>
                    </td>
                    <td colspan="5">{!! $dados->appends(Input::except('page'))->links() !!}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-center">{{ trans('relatorio-listagem.naoexisteresultado') }}</p>
    @endif
@endsection
