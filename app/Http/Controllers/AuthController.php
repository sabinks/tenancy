<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = Client::where('password_client', true)->first();
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:20'
        ]);
        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }
        $input = $request->only(['name', 'email', 'password']);
        User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'email_verified_at' => Carbon::now(),
            'remember_token' => '',
            'active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return $input;
    }



    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Authentication failed!'], 401);
        }

        $user = User::whereEmail($request->email)->first();




        if ($user->verification_token != NULL) {

            return response()->json([
                'message' => 'User email verification pending, please check your email!',
            ], 401);
        }

        if (!$user->active) {

            return response()->json([
                'message' => 'User not authorized or deactivated!',
            ], 401);
        }
        try {
            $domain = tenant()->domains[0]->domain;

            $response = Http::asForm()->post("$domain/oauth/token", [
                'grant_type' => 'password',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ]);
            return response([
                'access_token' => $response->json()['access_token'],
                'refresh_token' => $response->json()['refresh_token'],
                'expires_in' => $response->json()['expires_in'],
                'token_type' => $response->json()['token_type'],
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
