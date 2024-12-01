<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\Role;
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
        // $owners = User::permission('owner')->get();
        $users = User::all();
        $roles = [];
        foreach ($users as $user) {
            $roles = array_merge($roles, $user->getRoleNames()->toArray());
        }
        $roles_select = Role::all();
        return view('auth.admin-page', compact('users', 'roles', 'roles_select'));
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

        return redirect('/admin-page')->with('success', $user->name . 'さんを店舗代表者として登録しました。');
    }

    public function search(Request $request)
    {
        if ($request->has('reset')) {
            return redirect('/admin-page');
        }

        $query = User::query();
        $query = $this->getSearchQuery($request, $query);
        $users = $query->get();

        $roles = [];
        foreach ($users as $user) {
            $roles = array_merge($roles, $user->getRoleNames()->toArray());
        }

        $roles_select = Role::all();
        return view('auth.admin-page', compact('users', 'roles', 'roles_select'));
    }

    private function getSearchQuery($request, $query)
    {

        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if (!empty($request->keyword)) {
            $query->orWhere('email', 'like', '%' . $request->keyword . '%');
        }

        if (!empty($request->role)) {
            $query->role($request->role);
        }

        return $query;
    }
}
