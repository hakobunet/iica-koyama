<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;           // ← Student モデルを使えるようにする
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentCollection;
use Illuminate\Support\Facades\Auth; // 👈 この行が足りていないので追加します！

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// app/Http/Controllers/Api/AuthController.php
class AuthController extends Controller
{
    // POST /api/login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        
        // メール・パスワードを確認
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'メールアドレスまたはパスワードが違います',
            ], 401);
        }
        
        $user = Auth::user();
        
        // トークンを発行
        $token = $user->createToken('api-token')->plainTextToken;
        
        return response()->json([
            'status' => 'ok',
            'token'  => $token,
            'user'   => $user,
        ]);
    }
    
    // POST /api/logout
    public function logout(Request $request)
    {
        // 現在のトークンを削除
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['status' => 'ok', 'message' => 'Logged out']);
    }
    
    // GET /api/me
    public function me(Request $request)
    {
        return response()->json(['status' => 'ok', 'user' => $request->user()]);
    }

    public function register(Request $request)
    {
        // ① 入力チェック
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // ② ユーザーを作成（パスワードは必ずハッシュ化する）
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // ③ そのままログインさせたいので、トークンも発行して返す
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'ok',
            'token'  => $token,
            'user'   => $user,
        ], 201); // 201 = 新規作成に成功
    }
}