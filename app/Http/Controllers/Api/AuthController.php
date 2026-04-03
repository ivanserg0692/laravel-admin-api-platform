<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/register',
        tags: ['Auth'],
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequestBody')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthTokenResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation or captcha error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ], 201);
    }

    #[OA\Post(
        path: '/api/login',
        tags: ['Auth'],
        summary: 'Authenticate user and issue Sanctum token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/LoginRequestBody')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthTokenResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation, captcha, throttling or credential error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $request->authenticate();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    #[OA\Post(
        path: '/api/logout',
        tags: ['Auth'],
        summary: 'Logout current user',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logged out successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/LogoutResponse')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out.',
        ]);
    }

    #[OA\Get(
        path: '/api/me',
        tags: ['Auth'],
        summary: 'Get current authenticated user',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Current user',
                content: new OA\JsonContent(ref: '#/components/schemas/CurrentUserResponse')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
