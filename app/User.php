<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Emergency;
use App\Models\User_command;
use App\Models\User_officer;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'username', 'provider_id','phone','avatar','photo','birthday','gender','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function emergencys()
    {
        return $this->hasMany(Emergency::class, 'user_id', 'id');
    }

    public function userCommand()
    {
        return $this->hasOne(User_command::class, 'user_id', 'id');
    }

    public function userOfficer()
    {
        return $this->hasOne(User_officer::class, 'user_id', 'id');
    }
}
