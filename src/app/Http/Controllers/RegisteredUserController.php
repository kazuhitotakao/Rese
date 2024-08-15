<?php

namespace App\Http\Controllers;

use App\Http\Responses\RegisterResponse as ResponsesRegisterResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;

class RegisteredUserController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    public function store(
        Request $request,
        CreatesNewUsers $creator
    ): RegisterResponse {
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        event(new Registered($user = $creator->create($request->all())));

        // $this->guard->login($user);

        return app(ResponsesRegisterResponse::class);
    }

    public function thanks()
    {
        return view('auth.thanks');
    }


}
