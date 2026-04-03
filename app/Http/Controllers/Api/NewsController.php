<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class NewsController extends Controller
{
    #[OA\Get(
        path: '/api/news',
        tags: ['News'],
        summary: 'Get paginated news list',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1, example: 2)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 10, example: 15)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated news list',
                content: new OA\JsonContent(ref: '#/components/schemas/NewsIndexResponse')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->integer('per_page', 10), 100);
        $news = News::query()->paginate($perPage);

        return NewsResource::collection($news);
    }

    #[OA\Get(
        path: '/api/news/{news}',
        tags: ['News'],
        summary: 'Get single news item',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'news',
                description: 'News identifier',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Single news item',
                content: new OA\JsonContent(ref: '#/components/schemas/NewsShowResponse')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'News not found'),
        ]
    )]
    public function show(News $news)
    {
        return new NewsResource($news);
        //
    }
}
