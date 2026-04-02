<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Ivan'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ivan@example.com'),
        new OA\Property(property: 'is_blocked', type: 'boolean', example: false),
        new OA\Property(property: 'email_verified_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'AuthTokenResponse',
    properties: [
        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
        new OA\Property(property: 'token', type: 'string', example: '1|sanctum-token'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'CurrentUserResponse',
    properties: [
        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'LogoutResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Logged out.'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ValidationErrorResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(property: 'errors', type: 'object'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'RegisterRequestBody',
    required: ['name', 'email', 'password', 'password_confirmation'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Ivan'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ivan@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret12345'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'secret12345'),
        new OA\Property(property: 'cf-turnstile-response', type: 'string', example: 'turnstile-token'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'LoginRequestBody',
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ivan@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret12345'),
        new OA\Property(property: 'cf-turnstile-response', type: 'string', example: 'turnstile-token'),
    ],
    type: 'object'
)]
class AuthSchemas
{
}
