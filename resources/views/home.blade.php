@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">:: SISTEMA DE GENCIAMENTO DE PROTOCOLO  </div>

                <div class="panel-body">


                    <div class="row">

                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">

                                <div class="caption">
                                    <h3>PROTOCOLO</h3>

                                    <p>Para cadastrar um novo protocolo click no botão abaixo</p>
                                    <p> <a href="/protocolo/create" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>Novo</a>
                                        <a href="/protocolo/" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>Consultar</a></p>
                                </div>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>


@endsection


