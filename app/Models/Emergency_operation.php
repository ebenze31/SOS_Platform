<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emergency_operation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emergency_operations';

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
    protected $fillable = ['emergency_id', 'notify', 'command_by', 'operating_code', 'waiting_reply', 'officer_refuse', 'officer_no_respond', 'status', 'remark_status', 'area_id', 'user_officers_id', 'time_create_sos', 'time_command', 'time_go_to_help', 'time_to_the_scene', 'time_sos_success', 'time_sum_sos', 'photo_by_officer', 'remark_photo_by_officer', 'photo_succeed', 'remark_by_helper'];

    public function emergency()
    {
        return $this->belongsTo(Emergency::class, 'emergency_id', 'id');
    }

    public function officer()
    {
        return $this->belongsTo(User_command::class, 'notify', 'id');
    }
}
