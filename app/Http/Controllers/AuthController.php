<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();

        // Check password and if user exists
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'error' => true,
                'message' => 'Email ou senha inválida'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login efetuado com sucesso!',
            'user' => $user,
            'token' => $token
        ]);

    }

    public function register(Request $request){
        $user = User::where('email', $request->email)->first();

        if($user){
            return response()->json([
                'error' => true,
                'message' => 'Usuário já cadastrado'
            ]);
        }

        $new_user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $new_user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Conta criada com sucesso!',
            'user' => $new_user,
            'token' => $token
        ], 201);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout feito com sucesso.']);
    }
}
