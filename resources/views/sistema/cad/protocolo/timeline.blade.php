@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <!-- Page Header -->
        <header class="page-header" style="margin-top: 0;">
            <h1 style="margin-top: 0;">{{ trans('protocolo-exibe.codigo') }} #{{ $protocolo->protocolo }}</h1>
        </header>
        <!-- Protocol Data -->
        <div class="row protocol-data">
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.data') }}:</strong>
            </div>
            <div class="col-md-4">
                {{ date('d/m/Y H:i:s', strtotime($protocolo->data)) }}
            </div>
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.cliente') }}:</strong>
            </div>
            <div class="col-md-4">
                {{ $customer->name }}
            </div>
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.setor') }}:</strong>
            </div>
            <div class="col-md-4">
                {{ $protocolo->setor->nome }}
            </div>
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.solicitante') }}:</strong>
            </div>
            <div class="col-md-4">
                {{ $protocolo->solicitante }}
            </div>
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.solicitacao') }}:</strong>
            </div>
            <div class="col-md-10">
                {!! nl2br($protocolo->solicitacao) !!}
            </div>
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.status') }}:</strong>
            </div>
            <div class="col-md-4">
                <span class="label @if ($protocolo->status == 1) label-success @else label-danger @endif">{{ $protocolo->status == 1 ? trans('protocolo-exibe.concluido') : trans('protocolo-exibe.nao-concluido')}}</span>
            </div>
            @if ($protocolo->status == 1)
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.conclusao') }}:</strong>
            </div>
            <div class="col-md-4">
                {{ date('d/m/Y H:i:s', strToTime($protocolo->dum)) }}
            </div>
            @endif
        </div>
        <div class="row protocol-data">
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.arquivos' )}}:</strong>
            </div>
        </div>
        <div class="row protocol-data">
        @forelse($protocolo->attachments as $attachment)
            <div class="col-md-offset-2 col-md-10">
                <img src="{{ 'data:' . $attachment->file_type . ';base64,' . $attachment->file }}" class="img-responsive" />
            </div>
        @empty
            <div class="col-md-offset-2 col-md-10 text-left">
                <strong>Nenhum arquivo para este protocolo.</strong>
            </div>
        @endforelse
        </div>
        @if ($protocolo->arquivo_type != '')
        <div class="row protocol-data">
            <div class="col-md-2 text-right">
                <strong>{{ trans('protocolo-exibe.arquivo' )}}:</strong>
            </div>
            <div class="col-md-10">
                <img src="{{ 'data:' . $protocolo->arquivo_type . ';base64,' . $protocolo->arquivo }}" class="img-responsive" />
            </div>
        </div>
        @endif
        <!-- Protocol History Header -->
        <div class="page-header">
            <div class="clearfix">
                <h2 id="timeline" class="pull-left">{{ trans('protocolo-timeline.title') }}</h2>
                @if ($protocolo->status == 0)
                <a href="{{ url('/protocolo/' . $protocolo->id_protocolo . '/concluir/create') }}" class="btn btn-primary pull-right" style="margin-top: 20px;">{{ trans('protocolo-timeline.new') }}</a>
                @endif
            </div>
        </div>
        <!-- Protocol Timeline -->
        <div>
            <div class="timeline-centered">
            <?php $i = 0; ?>
            @foreach ($historicos as $historico)
                <article class="timeline-entry @if(!$i & 1) left-aligned @endif">
                      <div class="timeline-entry-inner">
                          <!-- History date/time -->
                          <time class="timeline-time">
                              <span>{{ date('d/m H:i', strtotime($historico->data)) }}</span>
                              <span>{{ (new Carbon\Carbon($historico->data))->formatLocalized('%A') }}</span>
                          </time>
                          <!-- History badge -->
                          @if ($historico->status == 1)
                              <div class="timeline-icon bg-success">
                                  <i class="entypo-feather"></i>
                              </div>
                          @else
                              <div class="timeline-icon bg-secondary">
                                  <i class="entypo-suitcase"></i>
                              </div>
                          @endif
                          <!-- History data -->
                          <div class="timeline-label">
                              @if (isset($users[$historico->id_usuario]))
                                  <h2>{{ trans('protocolo-timeline.user') }}: <a href="#">{{ $users[$historico->id_usuario]->username }}</a></h2>
                              @endif
                              <h2>{{ trans('protocolo-timeline.customer') }}:<a href="#"> {{ $customer->name }}</a></h2>
                              <h2>{{ trans('protocolo-timeline.protocol') }}:<a href="#"> {{ $protocolo->protocolo }}</a> </h2>
                              @if ($historico->id_setor_anterior > 0)
                                  <h2>{{ trans('protocolo-timeline.sector') }}: <span class="label label-danger">{{ $historico->setoranterior }}</span> -> <span class="label label-danger">{{ $historico->nome }}</span></h2>
                              @else
                                  <h2>{{ trans('protocolo-timeline.sector') }}: <span class="label label-danger">{{ $historico->nome }}</span></h2>
                              @endif
                              <h2>{{ trans('protocolo-timeline.plate') }}: <a href="#">{{$protocolo->placa}}</a></h2>
                              @if ($i == 0)
                                  <h2>{{ trans('protocolo-timeline.request') }}: </h2>
                              @else
                                  <h2>{{ trans('protocolo-timeline.sector_writed') }}: </h2>
                              @endif
                              <blockquote>{!! nl2br($historico->observacao)!!}</blockquote>
                              @if ($historico->status == 0)
                                  <h2>{{ trans('protocolo-timeline.estimate') }}: {{ date('d/m/Y', strtotime($historico->previsao)) }}</h2>
                              @endif

                              @if (sizeof($historico->attachments) > 0)
                                  @foreach($historico->attachments as $attachment)
                                      <img src="{{ 'data:' . $attachment->file_type . ';base64,' . $attachment->file }}"  class="img-responsive" />
                                  @endforeach
                              @endif
                              @if ($historico->arquivo_type != "")
                                  <img src="{{ 'data:' . $historico->arquivo_type . ';base64,' . $historico->arquivo }}"  class="img-responsive" />
                              @endif
                          </div>
                      </div>
                </article>
            <?php $i++; ?>
            @endforeach
                <article class="timeline-entry begin">
                    <div class="timeline-entry-inner">
                        <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                            <i class="entypo-flight"></i>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection
