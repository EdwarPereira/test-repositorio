<?php

namespace App\Repository;

use App\Entity\TicketAttachment;
use App\Ticket;

class TicketAttachmentRepository
{
    public function insert($file, Ticket $ticket)
    {
        $fileContent = base64_encode(file_get_contents($file->getRealPath()));
        $fileType = $file->getMimeType();
        $ticketAttachment = new TicketAttachment();
        $ticketAttachment->file = $fileContent;
        $ticketAttachment->file_type = $fileType;
        $ticketAttachment->ticket_id = $ticket->id_protocolo;
        $ticketAttachment->save();
        return $ticketAttachment;
    }
}