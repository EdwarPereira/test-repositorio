@extends('layouts.app')

@section('content')
    <header class="page-header header-no-margin">
        <div class="clearfix">
            <h1 class="pull-left">{{ trans('setor-lista.title') }}</h1>
            <a href="{{ url('/setor/create') }}" class="btn btn-primary pull-right btn-header-margin"><i class="fa fa-btn fa-plus"></i>{{trans('setor-lista.novo')}}</a>
        </div>
    </header>
    {!! Form::open(['method' => 'GET', 'url' => 'setor', 'class' => 'form-horizontal', 'role' => 'search']) !!}
        <div class="form-group">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ $search }}" name="search" placeholder="{{ trans('search.buscar') }}...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    @if (count($setores) > 0)
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th scope="col"><a href="/setor/?ordem=id&modo={{ $modo }}" id="ordenaid">{{ trans('setor-lista.id') }}</a></th>
                    <th scope="col"><a href="/setor/?ordem=nome&modo={{ $modo }}" id="ordenanome">{{ trans('setor-lista.nome') }}</a></th>
                    <th scope="col"><a href="/setor/?ordem=responsavel&modo={{ $modo }}" id="ordenaresponsavel">{{ trans('setor-lista.responsavel') }}</a></th>
                    <th scope="col"><a href="/setor/?ordem=email&modo={{ $modo }}" id="ordenaemail">{{ trans('setor-lista.email') }}</a></th>
                    <th scope="col"><a href="/setor/?ordem=tolerancia&modo={{ $modo }}" id="ordenatolerancia">{{ trans('setor-lista.tolerancia') }}</a></th>
                    <th scope="col"><a href="/setor/?ordem=status&modo={{ $modo }}" id="ordenanome">{{ trans('setor-lista.status') }}</a></th>
                    <th scope="col">&nbsp</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($setores as $setor)
                <tr>
                    <td class="table-text">{{ $setor->id_setor }}</td>
                    <td class="table-text">{{ $setor->nome }}</td>
                    <td class="table-text">{{ $setor->responsavel }}</td>
                    <td class="table-text">{{ $setor->email }}</td>
                    <td class="table-text">{{ $setor->tempo }}</td>
                    <td class="table-text">@if ($setor->status == '0') INATIVO @else ATIVO @endif</td>
                    <td class="table-text">
                        <a href="{{ url('/setor/'.$setor->id_setor.'/edit')}} "><i class="fa fa-btn fa-sign-out"></i>{{trans('setor-lista.editar')}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="6">{!! $setores->links() !!}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p class="text-center">{{ trans('setor-lista.naoexistenenhumsetor') }}</p>
    @endif
@endsection
