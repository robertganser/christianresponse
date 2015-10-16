<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	protected $fillable = array('email', 'password');

	public function getAuthIdentifier() {
		return $this -> getKey();
	}

	public function getAuthPassword() {
		return $this -> password;
	}

	public function getReminderEmail() {
		return $this -> email;
	}

}
