@extends('layouts.app')

@section('content')
    <header class="page-header header-no-margin">
        <div class="clearfix">
            <h1 class="pull-left">{{ $titulo }}</h1>
            <a href="{{ url('/setorusuario/create') }}" class="btn btn-primary pull-right btn-header-margin"><i class="fa fa-btn fa-plus"></i>{{ trans('setorusuario-lista.novo') }}</a>
        </div>
    </header>
    @if (count($setoresusuarios) > 0)
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th scope="col">{{ trans('setorusuario-lista.id') }}</th>
                    <th scope="col">{{ trans('setorusuario-lista.setor') }}</th>
                    <th scope="col">{{ trans('setorusuario-lista.usuario') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($setoresusuarios as $setorusuario)
                <tr>
                    <td class="table-text">
                        <div> {{ $setorusuario->id }} </div>
                    </td>
                    <td class="table-text">
                        <div> {{ $setorusuario->nome }} </div>
                    </td>
                    <td class="table-text">
                        <div> {{ $setorusuario->display_name }} </div>
                    </td>
                    <td class="table-text">
                        <div> <a href="{{ url('/setorusuario/'.$setorusuario->id.'/edit')}} "><i class="fa fa-btn fa-sign-out"></i>{{trans('setorusuario-lista.editar')}}</a> </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td colspan="4">{!! $setoresusuarios->links() !!}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-center">{{ trans('setorusuario-lista.naoexistenenhumsetorusuario') }}</p>
    @endif
@endsection
