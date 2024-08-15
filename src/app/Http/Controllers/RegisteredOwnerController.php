<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class RegisteredOwnerController  extends Controller
{
    use PasswordValidationRules;

    public function adminPage()
    {
        $owners = User::permission('owner')->get();
        return view('auth.admin-page', compact('owners'));
    }

    public function ownerRegister(Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $now = Carbon::now();
        $form = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => $now
        ];
        $user = User::create($form);
        $user->assignRole('owner');

        $owners = User::permission('owner')->get();

        return view('auth.admin-page', compact('owners'));
    }

    public function search(Request $request)
    {
        if ($request->has('reset')) {
            return redirect('/admin-page');
        }

        if (empty($request->keyword)) {
            $owners = User::permission('owner')->get();
        } else {
            $owners = User::permission('owner')
                ->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('email', 'like', '%' . $request->keyword . '%')
                ->get();
        }
        return view('auth.admin-page', compact('owners'));
    }
}
