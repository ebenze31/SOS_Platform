<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class User_officer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_officers';

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
    protected $fillable = ['name_officer', 'type', 'vehicle_type', 'level', 'amount_help', 'status', 'lat', 'lng', 'user_id', 'area_id','status_register','amount_refuse','last_update_location','line_notified_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function operations()
    {
        return $this->hasMany(Emergency_operation::class, 'user_officers_id', 'id');
    }

    public function current_operation()
    {
        return $this->hasOne(Emergency_operation::class, 'user_officers_id', 'id')
                    ->whereNotIn('status', ['เสร็จสิ้น', 'ยกเลิก'])
                    ->latest();
    }
}
