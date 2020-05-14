@extends('layouts.appsm')

@section('content')
    @if (!$id > 0)
    {!! Form::open(['method' => 'GET', 'url' => '/placa', 'class' => 'form-horizontal', 'role' => 'search']) !!}
    <div class="input-group custom-search-form" style="margin-bottom: 15px;">
        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="{{ trans('search.buscar') }}..." />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </span>
    </div>
    {!! Form::close() !!}
    @endif
    @if (count($vehicles) > 0)
        <table class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th scope="col">{{ trans('placa-lista.placa') }}</th>
                    <th scope="col">{{ trans('placa-lista.identificacao') }}</th>
                    <th scope="col">{{ trans('placa-lista.cliente') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
                    <td class="table-text">
                        <a href="#" data-dismiss="modal" onclick="window.parent.loadPlaca({{ $vehicle->vehicle_id }}, '{{ addslashes($vehicle->licplate) }}', {{ $vehicle->id_customer }}, '{{ addslashes($vehicle->name) }}', '')">{{ $vehicle->licplate }}</a>
                    </td>
                    <td class="table-text">{{ $vehicle->identification }}</td>
                    <td class="table-text">{{ $vehicle->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {!! $vehicles->appends(Input::except('page'))->links() !!}
    @else
        <p class="text-center">{{ trans('placa-lista.naoexistenenhumaplaca') . ' -> ' . $search }}</p>
    @endif
@endsection
