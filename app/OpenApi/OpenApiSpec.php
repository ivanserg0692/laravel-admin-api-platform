<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Laravel2026 API',
    description: 'API documentation for authentication endpoints.'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Use Sanctum token in the Authorization header: Bearer {token}'
)]
class OpenApiSpec
{
}
