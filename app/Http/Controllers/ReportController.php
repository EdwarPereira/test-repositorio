<?php

namespace App\Http\Controllers;

use App\Repository\TicketRepository;
use Illuminate\Http\Request;
use Carbon;

class ReportController extends Controller
{
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->middleware('auth');
        $this->ticketRepository = $ticketRepository;
    }
    public function index(Request $request)
    {
        $title = trans('relatorio-listagem.listagemsimples');

        $paginatedData = array();

        $page = \Request::get('page');

        if ($page != '') {
            $this->validate($request, [
                'datainicio' => 'required|date_format:d/m/Y',
                'datafim' => 'required|date_format:d/m/Y',
            ]);

            switch ($request->ordenarpor) {
                case 0:
                    $orderBy = 'protocolo.protocolo';
                    break;
                case 1:
                    $orderBy = 'protocolo.status';
                    break;
                case 2:
                    $orderBy = 'setor.nome';
                    break;
                case 3:
                    $orderBy = 'cliente_gt.name';
                    break;
            }

            $title = trans('relatorio-listagem.listagemsimples');

            $startDate = explode('/', $request->datainicio);
            $startDate = Carbon\Carbon::createFromDate($startDate[2], $startDate[1], $startDate[0])->hour(0)->minute(0)->second(0);

            $endDate = explode('/', $request->datafim);
            $endDate = Carbon\Carbon::createFromDate($endDate[2], $endDate[1], $endDate[0])->hour(23)->minute(59)->second(59);

            $paginatedData = $this->ticketRepository->paginatedReport($startDate, $endDate, $orderBy);

            $completeData = $this->ticketRepository->completeReport($startDate, $endDate, $orderBy);

            $totalTickets = 0;
            $pending = 0;
            $finished = 0;

            foreach ($completeData as $item ) {
                if ($item->status == 0) {
                    $pending++;
                } else {
                    $finished++;
                }
                $totalTickets++;
            }

            return view('sistema/rel/protocolo/exibe', [
                'titulo' => $title,
                'dados' => $paginatedData,
                'total' => $totalTickets,
                'pendente' => $pending,
                'concluido' => $finished,
            ]);
        } else {
            return view('sistema/rel/protocolo/filtro', [
                'titulo' => $title,
                'dados' => $paginatedData,
                'now' => Carbon\Carbon::now()
            ]);
        }
    }

    public function store(Request $request){

        $this->validate($request,[
            'datainicio'=>'required|date_format:d/m/Y',
            'datafim'=>'required|date_format:d/m/Y',
        ]);

        // ordenarpor   0 = protocolo
        //              1 = status
        //              2 = setor
        //              3 = cliente
        switch ($request->ordenarpor) {
            case 0:
                $orderBy = 'protocolo.protocolo';
                break;
            case 1:
                $orderBy = 'protocolo.status';
                break;
            case 2:
                $orderBy = 'setor.nome';
                break;
            case 3:
                $orderBy = 'cliente_gt.name';
                break;
        }

        $title = trans('relatorio-listagem.listagemsimples');

        $startDate = explode('/', $request->datainicio);
        $startDate = Carbon\Carbon::createFromDate($startDate[2], $startDate[1], $startDate[0])->hour(0)->minute(0)->second(0);

        $endDate = explode('/', $request->datafim);
        $endDate = Carbon\Carbon::createFromDate($endDate[2], $endDate[1], $endDate[0])->hour(23)->minute(59)->second(59);

        $paginatedData = $this->ticketRepository->paginatedReport($startDate, $endDate, $orderBy);

        $completeData = $this->ticketRepository->completeReport($startDate, $endDate, $orderBy);

        $totalTickets = 0;
        $pending  = 0;
        $finished = 0;

         foreach ($completeData as $item ) {
             if ($item->status == 0) {
                 $pending++;
             } else {
                 $finished++;
             }
             $totalTickets++;
         }

        return view('sistema/rel/protocolo/exibe', [
            'titulo' => $title,
            'dados' => $paginatedData,
            'total' => $totalTickets,
            'pendente' => $pending,
            'concluido' => $finished,
            'now' => Carbon\Carbon::now()
        ]);
    }
}
