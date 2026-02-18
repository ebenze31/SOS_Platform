<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emergencys';

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
    protected $fillable = ['name_reporter', 'type_reporter', 'phone_reporter', 'emergency_type', 'emergency_detail', 'emergency_lat', 'emergency_lng', 'emergency_location', 'emergency_photo', 'score_impression', 'score_period', 'score_total', 'comment_help'];

    
}
