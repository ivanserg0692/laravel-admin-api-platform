<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NewsResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'News title'),
        new OA\Property(property: 'slug', type: 'string', example: 'news-title'),
        new OA\Property(property: 'content', type: 'string', example: 'News content'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'deleted_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'NewsPaginationLinks',
    properties: [
        new OA\Property(property: 'first', type: 'string', nullable: true, example: 'http://localhost/api/news?page=1'),
        new OA\Property(property: 'last', type: 'string', nullable: true, example: 'http://localhost/api/news?page=10'),
        new OA\Property(property: 'prev', type: 'string', nullable: true, example: null),
        new OA\Property(property: 'next', type: 'string', nullable: true, example: 'http://localhost/api/news?page=2'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'NewsPaginationMeta',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(property: 'from', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'last_page', type: 'integer', example: 10),
        new OA\Property(property: 'path', type: 'string', example: 'http://localhost/api/news'),
        new OA\Property(property: 'per_page', type: 'integer', example: 10),
        new OA\Property(property: 'to', type: 'integer', nullable: true, example: 10),
        new OA\Property(property: 'total', type: 'integer', example: 100),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'NewsIndexResponse',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/NewsResource')
        ),
        new OA\Property(property: 'links', ref: '#/components/schemas/NewsPaginationLinks'),
        new OA\Property(property: 'meta', ref: '#/components/schemas/NewsPaginationMeta'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'NewsShowResponse',
    ref: '#/components/schemas/NewsResource'
)]
class NewsSchemas
{
}
