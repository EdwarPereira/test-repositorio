<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $primaryKey = 'id_protocolo';
    protected $table = 'protocolo';

    public function cliente()
    {
        return $this->hasOne('App\Customer','id_customer','id_cliente');
    }

    public function setor()
    {
        return $this->hasOne('App\Department','id_setor','id_setor');
    }

    public function attachments()
    {
        return $this->hasMany('App\Entity\TicketAttachment', 'ticket_id', 'id_protocolo');
    }
}
