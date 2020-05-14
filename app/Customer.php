<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'cliente_gt';
    protected $primaryKey = 'id_customer';
}
