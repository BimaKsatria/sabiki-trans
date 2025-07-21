<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SocialLoginController extends Controller
{
    
    public function handle(Request $request)
    {
        Log::info('Data dari Flutter:', $request->all());

        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'provider' => 'required|string',
        ]);

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name ?? 'Unknown',
                'email_verified_at' => now(),
                'provider' => $request->provider ?? 'google',
                'password' => bcrypt(Str::random(10)), // penting untuk social login
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}
