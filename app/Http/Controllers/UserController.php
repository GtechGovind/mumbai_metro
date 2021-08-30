<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PDOException;

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

        $user = User::all()->where('number', '=', $number)
            ->first();

        if (empty($user)) return json_encode([
            "status" => false,
            "message" => "User not found please register.",
            "code" => 101,
            "error" => "User not found"
        ], JSON_PRETTY_PRINT);

        else if ($user->email != $email) return json_encode([
            "status" => false,
            "message" => "Email does not match.",
            "code" => 102,
            "error" => "wrong email is provided!"
        ], JSON_PRETTY_PRINT);

        else return json_encode([
            "status" => true,
            "message" => "User exits, details fetched successfully.",
            "code" => 100,
            "user" => $user
        ], JSON_PRETTY_PRINT);

    }

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

        $user = User::all()->where('number', '=', $number)
            ->first();

        if (empty($user)) {

            $new_user = new User();

            $new_user->name = $name;
            $new_user->email = $email;
            $new_user->number = $number;
            $new_user->operator = $operator;

            try {

                $new_user->save();

                return json_encode([
                    "status" => true,
                    "message" => "User created successfully..",
                    "code" => 201,
                    "user" => $new_user
                ], JSON_PRETTY_PRINT);

            } catch (PDOException $e) {

                return json_encode([
                    "status" => false,
                    "message" => "Email is already registered please try with different email!",
                    "code" => 202,
                    "error" => $e->getMessage()
                ], JSON_PRETTY_PRINT);

            }


        } else return json_encode([
            "status" => false,
            "message" => "User already exits please log in.",
            "code" => 201,
            "error" => "User already exits please log in."
        ], JSON_PRETTY_PRINT);

    }
}
