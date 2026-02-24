<?php

namespace App\Support\Http;

use Illuminate\Http\Request;

class RequestContextResolver
{
    /**
     * @return array{url:string, query:array<string, mixed>}
     */
    public function resolveUrlAndQuery(Request $request): array
    {
        $url = $request->url();
        $query = $request->query();

        if (!$this->isLivewireRequest($request)) {
            return [
                'url' => $url,
                'query' => $query,
            ];
        }

        $referer = (string) $request->headers->get('referer', '');
        if ($referer === '') {
            return [
                'url' => $url,
                'query' => $query,
            ];
        }

        $refererRequest = Request::create($referer, 'GET');

        return [
            'url' => $refererRequest->url(),
            'query' => $refererRequest->query(),
        ];
    }

    private function isLivewireRequest(Request $request): bool
    {
        return trim($request->path(), '/') === 'livewire/update'
            || $request->headers->has('X-Livewire');
    }
}