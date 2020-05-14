<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $table = 'ticket_attachment';
    protected $primaryKey = 'id_attachment';
    public $timestamps = false;

    protected $fillable = ['ticket_id', 'file', 'file_type'];

    // TODO: Ao renomear as tabelas para inglês, mudar a associação
    public function ticket()
    {
        return $this->belongsTo('App\Ticket', 'ticket_id', 'id_protocolo');
    }
}