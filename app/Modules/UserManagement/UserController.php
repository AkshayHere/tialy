<?php

namespace App\Modules\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client as PassClient;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $email = request()->input('email', '');
        $name = request()->input('name', '');
        $password = request()->input('password', '');

        if (empty($email) || empty($name) || empty($password)) {
            return response()->json(['error' => 'Missing parameters !!']);
        }

        $host = 'http://localhost:8000';
        $client = PassClient::where('password_client', 1)->first();

        if (empty($client)) {
            return response()->json(['error' => 'Currently don\'t have a password client. Generate one before you continue.']);
        }
        $clientId = $client['id'];

        if (UserService::checkIfUserExist($email)) {
            $user = UserService::getUserByEmail($email);

            $email = $user['email'];
            $name = $user['name'];
            $password = $user['password'];

            // check for refersh token and handle it
            if (Cache::has($email)) {
                $out = Cache::get($email);
                Log::info('$out : ' . $out);

                $request->request->add([
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $out,
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'scope' => '*',
                ]);

                // Fire off the internal request.
                $token = Request::create(
                    'oauth/token',
                    'POST'
                );

                $response = Route::dispatch($token)->getContent();
                $responseData = json_decode($response, true);

                if (isset($responseData['refresh_token'])) {
                    Cache::put($email, $responseData['refresh_token'], 600);
                }

                return response()->json($responseData);
            } else {
                UserService::deleteUserByEmail($email);
                UserService::createUser($name, $email, $password);

                $request->request->add([
                    'grant_type' => 'password',
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ]);

                // Fire off the internal request.
                $token = Request::create(
                    'oauth/token',
                    'POST'
                );

                $response = Route::dispatch($token)->getContent();
                $responseData = json_decode($response, true);
                if (isset($responseData['refresh_token'])) {
                    Cache::put($email, $responseData['refresh_token'], 600);
                }

                return response()->json($responseData);
            }

        } else {
            $valid = validator($request->only('email', 'name', 'password', 'mobile'), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($valid->fails()) {
                $jsonError = response()->json($valid->errors()->all(), 400);
                return response()->json($jsonError);
            }

            $user = UserService::createUser($name, $email, $password);

            $request->request->add([
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ]);

            // Fire off the internal request.
            $token = Request::create(
                'oauth/token',
                'POST'
            );

            $response = Route::dispatch($token)->getContent();
            $responseData = json_decode($response, true);

            if (isset($responseData['refresh_token'])) {
                Cache::put($email, $responseData['refresh_token'], 600);
            }

            return response()->json($responseData);
        }
    }
}
