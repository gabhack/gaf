<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'roles_id', 'id_company', 'id_padre', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];
	
	
	public function rol()
	{
		return $this->hasOne('\App\Roles', 'id', 'roles_id');
	}
	
	public function padre()
	{
		return $this->hasOne('\App\User', 'id', 'users_id');
	}
	
	public function company()
	{
		return $this->hasOne('\App\User', 'id', 'id_company');
	}

    public function hasRole($rol) {
        if ($this->rol->rol == $rol) {
            return true;
        } else {
            return false;
        }
    }

    public function consultas()
    {
        return $this->hasMany('App\Consultas', 'users_id', 'id');
    }

    public function usuarioscompany()
    {
        return $this->hasMany('App\User', 'id_company', 'id');
    }

    public function usuarioshijos()
    {
        return $this->hasMany('App\User', 'id_padre', 'id');
    }
}
