<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        return response()->json(['msg' => 'registered successfully']);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $username = $validated['username'];
        $password = $validated['password'];
    
        $credentials = [
            'password' => $password,
        ];
    
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $username;
        } else {
            $credentials['username'] = $username;
        }
    
        return Auth::guard('web')->attempt($credentials)
            ? response()->json('success!')
            : response()->json([
                'error'    => trans('user_password_incorrect'),
                'username' => trans('is_incorrect_input'),
            ], 401);
    }
    



    public function getUser(Request $request)
    {
        return $request->user();
    }
}
