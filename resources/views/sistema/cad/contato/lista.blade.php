@extends('layouts.appsm')

@section('content')
    <div class="panel">
        @if (!$id > 0)
            @include('layouts.searchsm',['url' => 'contato', 'link' => 'contato'])
        @endif
        @if (count($contatos) > 0)
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                            <th scope="col">{{ trans('contato-lista.id') }}</th>
                            <th scope="col">{{ trans('contato-lista.nome') }}</th>
                            <th scope="col">{{ trans('contato-lista.email') }}</th>
                        </thead>
                        <tbody>
                        @foreach ($contatos as $contato)
                            <tr>
                                <td class="table-text">{{ $contato->contact_id }}</td>
                                <td class="table-text">
                                    <a href="#" data-dismiss="modal" onclick="window.parent.loadContato({{ $contato->contact_id }},'{{ addslashes($contato->name) }}','{{ $contato->email }}')">{{ $contato->name }}</a>
                                </td>
                                <td class="table-text">{{ $contato->email }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>{!! $contatos->appends(Input::except('page'))->links() !!}</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <br />
            <br />
            <div class="panel-body">{{ trans('contato-lista.naoexistenenhumcontato') . ' -> ' . $search }}</div>
        @endif
    </div>
@endsection
