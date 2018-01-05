<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Socialize;
use App\User;

class AuthController extends Controller {
    /**
    * @var Auth
    */
    private $auth;


    /**
    * Constructor. Laravel will inject the dependencies needed.
    *
    * @param \Illuminate\Contracts\Auth\Guard  $auth
    */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function login()
    {
        return view('auth/login');
    }

    public function logout()
    {
        $this->auth->logout();
        return redirect(action('AuthController@login'));
    }

    public function check(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($this->auth->attempt($credentials)) {
            return redirect()->intended('/');
        }
        return redirect(action('AuthController@login'))->withInput($request->only('email'));
    }

    public function google()
    {
        return Socialize::with('google')->redirect();
    }

    public function googleCallback()
    {
        $oauthUser = Socialize::with('google')->user();
        $user = User::firstOrCreate([
            'email' => $oauthUser->getEmail(),
            'password' => ''
        ]);
        $this->auth->login($user);
        return redirect()->intended('/');
    }

}
