<?php

namespace App\Standalone;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestType extends Model
{
    //
    protected $connection = 'mysql';
    use SoftDeletes;
}
