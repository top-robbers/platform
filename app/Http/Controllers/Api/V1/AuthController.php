<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $account = Account::query()
            ->where('email', $credentials['email'])
            ->first();

        if (!$account || !Hash::check($credentials['password'], $account->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        if (!$account->active) {
            return response()->json([
                'message' => 'Account is not active.',
            ], 403);
        }

        $token = $account->createToken('api')->plainTextToken;

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'data' => [
                'id' => $account->id,
                'name' => $account->name,
                'email' => $account->email,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $account = $request->user();

        return response()->json([
            'data' => [
                'id' => $account->id,
                'name' => $account->username,
                'email' => $account->email,
                'created_at' => $account->created_at?->toISOString(),
            ],
        ]);
    }
}