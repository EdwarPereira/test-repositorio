<?php

namespace App\Repository;

use App\Entity\HistoryAttachment;
use App\TicketHistory;

class HistoryAttachmentRepository
{
    public function insert($file, TicketHistory $ticketHistory)
    {
        $fileContent = base64_encode(file_get_contents($file->getRealPath()));
        $fileType = $file->getMimeType();
        $historyAttachment = new HistoryAttachment();
        $historyAttachment->file = $fileContent;
        $historyAttachment->file_type = $fileType;
        $historyAttachment->history_id = $ticketHistory->id_historico;
        $historyAttachment->save();
        return $historyAttachment;
    }
}