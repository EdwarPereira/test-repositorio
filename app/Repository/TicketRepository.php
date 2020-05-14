<?php

namespace App\Repository;

use App\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TicketRepository
{
    public function findByIdWithCustomerAndDepartment($id)
    {
        $protocol = DB::table('protocolo')
            ->join('cliente_gt', 'protocolo.id_cliente', '=', 'cliente_gt.id_customer')
            ->join('setor', 'protocolo.id_setor', '=', 'setor.id_setor')
            ->select('protocolo.*', 'cliente_gt.name', 'setor.nome')
            ->where('id_protocolo', '=', $id)
            ->get();
        return $protocol;
    }

    public function searchByDepartmentIdAndProtocolNumber($departmentId, $search, $status, $orderBy, $mode)
    {
        $query = DB::table('protocolo')
            ->join('setor', 'protocolo.id_setor', '=', 'setor.id_setor')
            ->where('protocolo.id_setor', '=', $departmentId);
        if (trim($search) != "") {
            $query = $query->where('protocolo.protocolo', 'like', '%' . $search . '%');
        } else {
            if ($status == 'pendentes') {
                $query = $query->where('protocolo.status', '=', '0');
            } else if ($status == 'concluidos') {
                $query = $query->where('protocolo.status', '=', '1');
            }
        }
        return $query->select('protocolo.*', 'setor.nome')
            ->orderBy($orderBy, $mode)
            ->paginate(config('app.paginacao'));
    }

    public function searchByDepartmentsAndProtocolNumber($departmentsList, $search, $status, $orderBy, $mode)
    {
        $query = DB::table('protocolo')
            ->join('setor', 'protocolo.id_setor', '=', 'setor.id_setor')
            ->whereIn('protocolo.id_setor', explode(',', $departmentsList));

        if (trim($search) != "") {
            $query = $query->where('protocolo', 'like', '%' . $search . '%');
        } else {
            if ($status == 'pendentes') {
                $query = $query->where('protocolo.status', '=', '0')
                    ->orderBy('protocolo.dum', 'asc');
            } else if ($status == 'concluidos') {
                $query = $query->where('protocolo.status', '=', '1');
            }
        }

        return $query->select('protocolo.*', 'setor.nome')
            ->orderBy($orderBy, $mode)
            ->paginate(config('app.paginacao'));
    }

    public function paginatedReport($startDate, $endDate, $orderBy)
    {
        return DB::table('protocolo')
            ->join('cliente_gt', 'protocolo.id_cliente', '=', 'cliente_gt.id_customer')
            ->join('setor', 'protocolo.id_setor', '=', 'setor.id_setor')
            ->select('protocolo.*', 'cliente_gt.name', 'setor.nome')
            ->whereBetween('protocolo.data', [$startDate, $endDate])
            ->orderBy($orderBy, 'asc')
            ->paginate(100);
    }

    public function completeReport($startDate, $endDate, $orderBy)
    {
        return  DB::table('protocolo')
            ->join('setor', 'protocolo.id_setor', '=', 'setor.id_setor')
            ->select('protocolo.*', 'setor.nome')
            ->whereBetween('protocolo.data', [$startDate, $endDate])
            ->orderBy($orderBy, 'asc')
            ->get();
    }

    /**
     * Find a ticket by its ID
     * @param $id
     * @return Ticket
     */
    public function findById($id)
    {
        return Ticket::find($id);
    }

    public function insert($data, $currentDate, $idProtocol, $emailList)
    {
        $ticket = new Ticket();

        $ticket->data = $currentDate;
        $ticket->protocolo = $idProtocol;
        $ticket->id_cliente = $data->id_cliente;
        $ticket->id_usuario_cad = $data->id_usuario_cad;
        $ticket->id_setor = $data->id_setor;
        $ticket->solicitante = $data->solicitante;
        $ticket->solicitacao = $data->solicitacao;
        $ticket->email = $emailList;
        $ticket->placa = $data->placa;

        if ($data->id_veiculo > 0) {
            $ticket->id_veiculo = $data->id_veiculo;
        }

        $ticket->status = 0;
        $ticket->dum = $currentDate;

        $ticket->save();
        return $ticket;
    }

    public function update(Ticket $ticket, $data)
    {
        $ticket->data = strtoupper($data->data);
        $ticket->id_cliente = $data->id_cliente;
        $ticket->id_usuario_cad = $data->id_usuario_cad;
        $ticket->id_setor = $data->id_setor;
        $ticket->solicitante = $data->solicitante;
        $ticket->solicitacao = $data->solicitacao;
        $ticket->arquivo = $data->arquivo;
        $ticket->status = $data->status;
        $ticket->email = $data->email;

        return $ticket->save();
    }

    public function updateFromHistory($ticket, $ticketHistory, $data)
    {
        $ticket->id_setor = $ticketHistory->id_setor;

        if ($ticketHistory->status == 1) {
            $ticket->status = 1;
            $ticket->dum = Carbon::now();
        } else {
            //data da ultima movimentacao
            if ($data->previsao != '') {
                $ticket->dum = $ticketHistory->previsao;
            } else {
                $ticket->dum = Carbon::now();
            }
        }

        if ($ticket->status == 1 && $data->concluido == "0") {
            $ticket->status = 0;
        }

        $ticket->save();
        return $ticket;
    }
}