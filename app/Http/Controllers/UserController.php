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

    public function getDecode()
    {

        $code = base64_decode("2f9fe1cebec5ed6fd633c05fd875ea162b247d2038df7da850ecc87a031bc394aab19b199cd867b62dead91ecc77c0d8097e7e43c76db6caba1f4777f4f8c171cb20fef4b123024f8175d1105c7e5ae5119eca938d865ac878d2142c241acdde427c3c40d86b7853a907a3c7bb002a3f~");

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', "fedcba9876543210");
        mcrypt_generic_init($td, "0123456789abcdef", "fedcba9876543210");

        $decrypted = mdecrypt_generic($td, $code);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        echo utf8_encode(trim($decrypted));

    }
}
