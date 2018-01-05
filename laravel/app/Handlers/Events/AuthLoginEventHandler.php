<?php namespace App\Handlers\Events;

use App\User;
use Log;

class AuthLoginEventHandler {

	public function handle(User $user)
	{
            Log::info($user->email . ' logged');
	}

}
