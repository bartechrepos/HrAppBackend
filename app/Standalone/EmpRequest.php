<?php

namespace App\Standalone;

use Illuminate\Database\Eloquent\Model;

class EmpRequest extends Model
{
    //
    protected $connection = 'mysql';

    public function employee()
    {
        return $this->belongsTo('App\Standalone\Employee');
    }

    public function resp_employee()
    {
        return $this->belongsTo('App\Standalone\Employee');
    }


    public function request_type()
    {
        return $this->belongsTo('App\Standalone\RequestType');
    }

    public function to_department()
    {
        return $this->belongsTo('App\Department','to_dep_id', 'id');
    }

}
