<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Validation error")
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ], 'User registered successfully', 201);
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ], 'Login successful');
    }

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     summary="Get current user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user data",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function me(Request $request)
    {
        return $this->success($request->user(), 'Current user');
    }

    /**
     * @OA\Post(
     *     path="/auth/tokens",
     *     summary="Create API token",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="My API Token"),
     *             @OA\Property(property="abilities", type="array", @OA\Items(type="string"), example={"read", "write"}),
     *             @OA\Property(property="expires_at", type="string", format="date", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Token created"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function createToken(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'array',
            'expires_at' => 'nullable|date',
        ]);

        $token = PersonalAccessToken::generateToken(
            auth()->user(),
            $validated['name'],
            $validated['abilities'] ?? ['read', 'write']
        );

        return $this->success($token, 'Token created', 201);
    }

    /**
     * @OA\Get(
     *     path="/auth/tokens",
     *     summary="List user tokens",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Tokens retrieved"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function listTokens()
    {
        $tokens = auth()->user()->personalAccessTokens()->get();
        return $this->success($tokens, 'Tokens retrieved');
    }

    /**
     * @OA\Delete(
     *     path="/auth/tokens/{token}",
     *     summary="Delete API token",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Token deleted"),
     *     @OA\Response(response=404, description="Token not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function deleteToken($tokenId)
    {
        $token = PersonalAccessToken::find($tokenId);

        if (!$token || $token->user_id !== auth()->id()) {
            return $this->error('Token not found', 404);
        }

        $token->delete();
        return $this->success(null, 'Token deleted');
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Logged out successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success(null, 'Logged out successfully');
    }
}