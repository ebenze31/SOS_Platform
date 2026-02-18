<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_command extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_commands';

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
    protected $fillable = ['name_command', 'command_role', 'number', 'status', 'creator', 'user_id'];

    
}
