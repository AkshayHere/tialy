<?php

namespace App\Modules\UserManagement;

use App\Models\User;

class UserService
{
    public static function createUser(string $name, string $email, string $password):?User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }
    
    public static function checkIfUserExist(string $email):bool
    {
        return User::where('email', $email)->exists();
    }
    
    public static function getUserByEmail(string $email):?User
    {
        return User::where('email', $email)->first();
    }

    public static function deleteUserByEmail(string $email)
    {
        return User::where('email', $email)->delete();
    }
}
