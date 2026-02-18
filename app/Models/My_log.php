<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class My_log extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'my_logs';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['line', 'title', 'content'];

    
}
