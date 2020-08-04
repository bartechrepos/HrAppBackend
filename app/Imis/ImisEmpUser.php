<?php

namespace App\Imis;

use Illuminate\Database\Eloquent\Model;

class ImisEmpUser extends Model
{
    protected $table = 'TBL_Employee';
    public $timestamps = false;
    protected $primaryKey = "ID";
}
