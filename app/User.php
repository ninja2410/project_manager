<?php

namespace App;

use App\Traits\UserTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use UserTrait;
    public function roles()
    {
        return $this->belongsToMany('App\Role','user_roles','user_id','role_id');
    }
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function getBirthdateAttribute($date)
    {
        if(strtotime($date) !=null)
            return date('d/m/Y', strtotime($date));

        return '01/01/1900';
    }

    /**
     * Verifica si el usuario tiene un permiso especifico
     * @param $description : del permiso a verificar
     * @return bool
     */
    public function verifyPermission($description){
        return $this->validPermission($this, $description);
    }

    public function getDateHireAttribute($date)
    {
        if(strtotime($date) !=null)
            return date('d/m/Y', strtotime($date));

        return '01/01/1900';
    }

    public function getDateDissmisalAttribute($date)
    {
        if(strtotime($date) !=null)
            return date('d/m/Y', strtotime($date));

        return '01/01/1900';
    }
}
