<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;
    protected $table = "users";
    /*** The attributes that are mass assignable.*/
    protected $fillable = [
        'id',
        'username',
        'log_email',
        'password',
        'password_hashed',
        'phone',
        'role_id',
        'uni_id',
        'fac_id',
        'dept_id',
//        'lab_id',
//        'UFId',
        'name',
        'email',
        'ImagePath',
        'loginCount',
//        'phone',
//        'central'
    ];

    /*** The attributes that should be hidden for arrays.*/
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    function has_role($role){
        return $this->hasRole($role);
    }
    function assign_role($role){
        return $this->assignRole($role);
    }
    function role(){
        return $this->belongsTo(Role::class);
    }
    function university(){
        return $this->belongsTo(universitys::class);
    }
    function faculty(){
        return $this->belongsTo(facultys::class);
    }
    function department(){
        return $this->belongsTo(departments::class);
    }
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }
}
