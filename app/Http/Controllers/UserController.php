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

        $user = User::all()->where('email', '=', $email)
            ->where('number', '=', $number)
            ->first();

        if (empty($user)) {

            return json_encode([
                "status" => false,
                "message" => "User not found",
                "error" => "User not found please register"
            ], JSON_PRETTY_PRINT);

        } else {


            return json_encode([
                "status" => true,
                "message" => "User exits",
                "user" => $user
            ], JSON_PRETTY_PRINT);

        }

    }

    /** @noinspection PhpUndefinedFieldInspection */
    function addUser(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'number' => 'required',
            'operator' => 'required'
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $number = $request->input('number');
        $operator = $request->input('operator');

        $user = User::all() ->where('email', '=', $email)
                            ->where('number', '=', $number)
                            ->first();

        if (empty($user)) {

            $new_user = new User();

            $new_user->name = $name;
            $new_user->email = $email;
            $new_user->number = $number;
            $new_user->operator = $operator;

            $new_user->save();

            return json_encode([
                "status" => true,
                "message" => "New user is created",
                "user" => $new_user
            ], JSON_PRETTY_PRINT);

        } else {

            return json_encode([
                "status" => true,
                "message" => "User already exits",
                "user" => $user
            ], JSON_PRETTY_PRINT);

        }

    }
}
