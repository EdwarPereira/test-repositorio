@extends('layouts.appsm')

@section('content')
    {!! Form::open(['method' => 'GET', 'url' => '/cliente', 'class' => 'form-horizontal', 'role' => 'search']) !!}
    <div class="input-group custom-search-form" style="margin-bottom: 15px;">
        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="{{ trans('search.buscar') }}..." />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </span>
    </div>
    {!! Form::close() !!}
    @if (count($customers) > 0)
        <table class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th scope="col">{{ trans('cliente-lista.id') }}</th>
                    <th scope="col">{{ trans('cliente-lista.name') }}</th>
                    <th scope="col">{{ trans('cliente-lista.contato') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td class="table-text">{{ $customer->id_customer }}</td>
                    <td class="table-text">
                        @if(strpos($customer->emails, ',') !== false)
                        <a href="#" data-dismiss="modal" onclick="window.parent.loadClient({{ $customer->id_customer }}, '{{ addslashes($customer->name) }}', '{{ $customer->emails }}')">
                            {{ $customer->name }}
                        </a>
                        @else
                            {{ $customer->name }}
                        @endif
                    </td>
                    <td class="table-text">{!! strpos($customer->emails, ',') !== false ? nl2br(implode("\n", explode(',', $customer->emails)))  : '<span class="text-danger">' . trans('cliente-lista.no_contact') . '</span>'  !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {!! $customers->appends(Input::except('page'))->links() !!}
    @else
        <div class="text-center">{{ trans('cliente-lista.naoexistenenhumcliente') . ' -> ' . $search }} </div>
    @endif
@endsection
