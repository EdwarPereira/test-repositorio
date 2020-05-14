<?php

namespace App\Repository;

use App\TicketHistory;
use Carbon\Carbon;

class TicketHistoryRepository
{
    public function findByTicketId($ticketId)
    {
        return TicketHistory::query()
            ->join('protocolo', 'historico.id_protocolo', '=', 'protocolo.id_protocolo')
            ->join('setor', 'historico.id_setor', '=', 'setor.id_setor')
            ->leftjoin('setor as B', 'historico.id_setor_anterior', '=', 'B.id_setor')
            ->select('historico.*', 'protocolo.protocolo', 'setor.nome', 'B.nome as setoranterior')
            ->where('protocolo.id_protocolo', '=', $ticketId)
            ->orderBy('historico.data', 'desc')
            ->get();
    }

    public function insert($ticket)
    {
        $ticketHistory = new TicketHistory();
        $ticketHistory->id_protocolo = $ticket->id_protocolo;
        $ticketHistory->data = $ticket->data;
        $ticketHistory->observacao = $ticket->solicitacao;
        $ticketHistory->id_usuario = $ticket->id_usuario_cad;
        $ticketHistory->id_setor = $ticket->id_setor;
        $ticketHistory->previsao = $ticket->data;
        $ticketHistory->status = 0;

        $ticketHistory->save();
        return $ticketHistory;
    }

    public function insertFromTicketUpdate($ticket, $data)
    {
        $ticketHistory = new TicketHistory();
        $ticketHistory->data = Carbon::now();
        $ticketHistory->id_protocolo = $data->id_protocolo;

        if ($data->concluido == 1) {
            $ticketHistory->previsao = $data->data;
        } else {
            if ($ticketHistory->previsao != '') {
                $ticketHistory->previsao = $data->previsao;
            }
        }

        $ticketHistory->observacao = $data->observacao;
        $ticketHistory->id_usuario = $data->id_usuario;
        $ticketHistory->id_setor_anterior = 0;

        if ($data->id_setor != $ticket->id_setor) {
            $ticketHistory->id_setor_anterior = $ticket->id_setor;
        }

        $ticketHistory->id_setor = $data->id_setor;
        $prediction = Carbon::now();

        if ($data->previsao != '') {
            $prediction = explode('/', $data->previsao);
            $prediction = Carbon::createFromDate($prediction[2], $prediction[1], $prediction[0]);
        }

        if ($data->previsao != '') {
            $ticketHistory->previsao = $prediction;
        } else {
            $ticketHistory->previsao = Carbon::now();
        }

        if ($data->concluido == 1) {
            $ticketHistory->status = 1;
        } else {
            $ticketHistory->status = 0;
        }

        $ticketHistory->save();
        return $ticketHistory;
    }
}