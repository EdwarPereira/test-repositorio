<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class HistoryAttachment extends Model
{
    protected $table = 'history_attachment';
    protected $primaryKey = 'id_attachment';
    public $timestamps = false;

    protected $fillable = ['history_id', 'file', 'file_type'];

    public function ticket()
    {
        return $this->belongsTo('App\TicketHistory', 'history_id', 'id_historico');
    }
}