<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Appraising;
use App\Models\User;
use App\Services\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{

    public function getRegisterPage(){
        return view("user.register");
    }

    public function register(Request $request)
    {
        $request->validate([
            "email" => "email|required",
            "phone" => ["regex:/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/m", "required"],
            "password" => "required|confirmed|min:6"
            ]);
        $user = new User();
        $user->fill($request->all());
        $user->percent = 20;
        $user->role = "client";
        $user->save();
        \auth()->login($user);
        return Redirect::route("index");
    }

    public function getLoginPage()
    {
        return view("user.login");
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect("/");
        }
        return Redirect::back()->withErrors(['Ошибка авторизации', 'Неверно введен логин или пароль']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function users()
    {
        $users = User::whereIn("role", ["dealer", "client"])->get();
        return view("user.index", ["users" => $users]);
    }

    public function create(){
        return view("user.create", ["roles" => ["dealer", "client", "admin"]]);
    }

    public function store(UserRequest $request){
        $user = new User();
        $user->fill($request->all());
        $user->password = \Hash::make($request->get("password"));
        if ($request->get("role") == "dealer"){
            $user->percent = $request->get("percent");
        }
        $user->save();
        return Redirect::route("users.index");
    }

    public function edit($id)
    {
        $profile = User::find($id);
        $roles = Response::getRoles();
        return view("user.edit", ["profile" => $profile, "roles" => $roles]);
    }

    public function update($id, UserRequest $request): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->all());
        $user->save();
        return Redirect::route("users.index");
    }

    public function destroy($id): RedirectResponse
    {
        Appraising::where("user_id", $id)->delete();
        User::destroy($id);
        return Redirect::route("users.index");
    }

}
