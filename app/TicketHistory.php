<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $primaryKey = 'id_historico';
    protected $table = 'historico';

    public function attachments()
    {
        return $this->hasMany('App\Entity\HistoryAttachment', 'history_id', 'id_historico');
    }
}
