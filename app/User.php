<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'usuario_gt';

    protected $primaryKey = 'user_id';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getUserId()
    {
        return $this->user_id;
    }

    public function isSuperAdmin()
    {
        if ($this->user_id == 0 || $this->user_id == 13 || $this->user_id == 65 || strtoupper($this->username) == 'FERNANDO') {
            return true;
        } else {
            return false;
        }
    }
}
