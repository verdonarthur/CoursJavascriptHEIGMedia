<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

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
	protected $fillable = ['email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];

        /**
        * Ignore remember token
        */
        public function setAttribute($key, $value)
        {
            if ($key != $this->getRememberTokenName()) {
                parent::setAttribute($key, $value);
            }
        }

        public function items()
        {
            return $this->hasMany('App\Item');
        }

        public function roles()
        {
            return $this->belongsToMany('App\Role')->withTimestamps();
        }

        public function hasRole($roleLabel)
        {
            $role = $this->roles()->whereLabel($roleLabel)->first();
            return isset($role);
        }

        public function hasPermission($permissionLabel)
        {
            foreach ($this->roles as $role) {
                if ($role->hasPermission($permissionLabel)) {
                    return true;
                }
            }
            return false;
        }

}
