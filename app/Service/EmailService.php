<?php

namespace App\Service;

use App\Email;
use App\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

/**
 * Class EmailService
 * TODO: Melhorar a parte de títulos e refatorar para eliminar duplicações
 * @package App\Service
 */
class EmailService
{
    private $from;

    public function __construct()
    {
        $this->from = config('app.email');
    }

    public function sendEmails(Ticket $ticket, $department, $customer)
    {
        $data = $this->extractData($ticket, $department);
        $data['titulo_interno'] = 'GLOBALSAT - SETOR (' . $department->nome . ') - PROTOCOLO N.: ' . $ticket->protocolo;
        $data['titulo_externo'] = 'GLOBALSAT - PROTOCOLO N.: ' . $ticket->protocolo;
        $this->sendExternalEmail($data, $ticket, $department, $customer, 'sistema.emails.externo');
        $this->sendInternalEmail($data, $ticket, $department, $customer, 'sistema.emails.interno');
    }

    public function sendEmailsFromTicketUpdate(Ticket $ticket, $department, $customer)
    {
        $data = $this->extractData($ticket, $department);
        if ($ticket->status == 0) {
            $data['titulo_interno']   = '[GLOBALSAT] ALTERAÇÃO DE PROTOCOLO';
            $data['titulo_externo']   = '[GLOBALSAT] ALTERAÇÃO DE PROTOCOLO';
            $this->sendInternalEmail($data, $ticket, $department, $customer, 'sistema.emails.alteracao');
        } else {
            $data['titulo_interno']   = '[GLOBALSAT] CONCLUSÃO DE PROTOCOLO';
            $data['titulo_externo']   = '[GLOBALSAT] CONCLUSÃO DE PROTOCOLO';
            $this->sendExternalEmail($data, $ticket, $department, $customer, 'sistema.emails.concluido');
        }
    }
    /**
     * Send an e-mail about the Ticket to GlobalSat internal staff
     *
     * @param Ticket $ticket
     */
    protected function sendInternalEmail($data, Ticket $ticket, $department, $customer, $viewFile)
    {

        Mail::send($viewFile, [
            'setor' => $department->nome,
            'responsavel' => $department->responsavel,
            'cliente' => strtoupper($customer->name),
            'protocolo' => $ticket->protocolo,
            'solicitacao' => $ticket->solicitacao,
            'link' => '/protocolo/' . $ticket->id_protocolo . '/timeline'
        ], function ($message) use ($data) {
            $message->from($data['from'], $data['fromnome']);
            foreach ($data['setoremail'] as $key => $value) {
                $message->to($value, $data['setornome']);
            }

            $message->subject($data['titulo_interno']);
            foreach ($data['setoremail'] as $key => $value) {
                $message->replyTo($value, $data['replynome']);
            }
        });

        $renderedInternalEmail = View::make($viewFile, [
            'setor' => $department->nome,
            'responsavel' => $department->responsavel,
            'cliente' => strtoupper($customer->name),
            'protocolo' => $ticket->protocolo,
            'solicitacao' => $ticket->solicitacao,
            'link' => '/protocolo/' . $ticket->id_protocolo . '/timeline'
        ]);

        $this->generateLog($renderedInternalEmail, $data, $department->email, $data['titulo_interno']);
    }
    /**
     * Send an e-mail about the Ticket to the customer requesting support
     * @param Ticket $ticket
     */
    protected function sendExternalEmail($data, Ticket $ticket, $department, $customer, $viewFile)
    {
        Mail::send($viewFile, [
            'setor' => $department->nome,
            'solicitante' => $ticket->solicitante,
            'cliente' => strtoupper($customer->name),
            'protocolo' => $ticket->protocolo,
            'solicitacao' => $ticket->solicitacao,
            'link' => '/protocolo/' . $ticket->id_protocolo
        ], function ($message) use ($data) {
            $message->from($data['from'], $data['fromnome']);
            foreach ($data['solicitanteemail'] as $key => $value) {
                $message->to($value, $data['solicitante']);
            }

            $message->subject($data['titulo_externo']);
            foreach ($data['setoremail'] as $key => $value) {
                $message->replyTo($value, $data['replynome']);
            }
        });

        $renderedExternalEmail = View::make($viewFile, [
            'setor' => $department->nome,
            'solicitante' => $ticket->solicitante,
            'cliente' => strtoupper($customer->name),
            'protocolo' => $ticket->protocolo,
            'solicitacao' => $ticket->solicitacao,
            'link' => '/protocolo/' . $ticket->id_protocolo
        ]);

        $this->generateLog($renderedExternalEmail, $data, $ticket->email, $data['titulo_externo']);
    }

    protected function extractData(Ticket $ticket, $department)
    {
        $data = array();
        $data['setor']      = $department->nome;
        $data['setoremail'] = explode(',', $department->email);
        $data['setornome']  = $department->responsavel;
        $data['from'] = $this->from;
        $data['reply'] = $department->email;
        $data['replynome'] = $department->responsavel;
        $data['fromnome'] = 'Globalsat';
        $data['solicitante'] = $ticket->solicitante;
        $data['solicitacao'] = $ticket->solicitacao;
        $data['protocolo'] = $ticket->id_protocolo;
        $data['id_protocolo'] = $ticket->id_protocolo;
        $emailList = explode(',', $ticket->email);
        $data['solicitanteemail'] = $emailList;

        return $data;
    }

    protected function generateLog($renderedView, $data, $emailRecipient, $subject)
    {
        $emailLog = new Email();
        $emailLog->from = $data['from'];
        $emailLog->fromname = $data['fromnome'];
        $emailLog->to = $emailRecipient;
        $emailLog->toname = $data['setornome'];
        $emailLog->replyto = $data['reply'];
        $emailLog->replytoname = $data['replynome'];
        $emailLog->subject = $subject;
        $emailLog->body = (string) $renderedView;
        $emailLog->id_protocolo = $data['id_protocolo'];
        $emailLog->status = 0;
        $emailLog->save();
    }
}