<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function getAllUsers()
    {
        return User::all();
    }

    function getUser(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'number' => 'required'
        ]);

        $email = $request->input('email');
        $number = $request->input('number');

        return User::all()->where('email', '=', $email)
            ->where('number', '=', $number)
            ->firstOrFail();

    }

    function addUser(Request $request): User
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'number' => 'required'
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $number = $request->input('number');

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->number = $number;
        $user->number_verified_at = time();

        $user->save();

        return $user;

    }
}
