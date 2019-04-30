<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Notifications\Authenticated;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/posts';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request Request
     * @param mixed   $user    User
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $request = $request;
        \Notification::send($user, new Authenticated());
        // \Notification::route('mail', 'datamini01@gmail.com')
        //     ->route('nexmo', '5555555555')
        //     ->notify(new Authenticated());
    }
}
