<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function emergency_operations()
    {
        return $this->hasMany(Emergency_operation::class, 'command_by', 'id');
    }

    public function creator_info()
    {
        return $this->belongsTo(User_command::class, 'creator', 'user_id');
    }
}
