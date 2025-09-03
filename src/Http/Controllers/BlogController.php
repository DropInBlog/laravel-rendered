<?php

namespace DropInBlog\Laravel\Http\Controllers;

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class BlogController extends Controller
{

    public function index(?int $page = 1): Renderable
    {
        return $this->fetchAndRenderView('/list', 'index', ['page' => $page, 'fields' => config('dropinblog.response_fields')]);
    }

    public function category(string $slug, ?int $page = 1): Renderable
    {
        return $this->fetchAndRenderView("/list/category/{$slug}", 'category', ['page' => $page, 'fields' => config('dropinblog.response_fields')]);
    }

    public function author(string $slug, ?int $page = 1): Renderable
    {
        return $this->fetchAndRenderView("/list/author/{$slug}", 'author', ['page' => $page, 'fields' => config('dropinblog.response_fields')]);
    }

    public function post($slug): Renderable
    {
        return $this->fetchAndRenderView("/post/{$slug}", 'post', ['fields' => config('dropinblog.response_fields')]);
    }

    public function sitemap(): Response
    {
        return $this->fetchAndReturnResponse('/sitemap');
    }

    public function feed(): Response
    {
        return $this->fetchAndReturnResponse('/feed', $this->getFeedParams());
    }

    public function feedCategory(string $slug): Response
    {
        return $this->fetchAndReturnResponse("/feed/category/{$slug}", $this->getFeedParams());
    }

    public function feedAuthor(string $slug): Response
    {
        return $this->fetchAndReturnResponse("/feed/author/{$slug}", $this->getFeedParams());
    }

    private function getHttpClient()
    {
        $blogId = config('dropinblog.id');
        $apiToken = config('dropinblog.api_token');

        if (empty($blogId) || empty($apiToken)) {
            abort(500, 'DropInBlog configuration is incomplete. Please set DROPINBLOG_ID and DROPINBLOG_API_TOKEN in your .env file.');
        }

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->withoutVerifying()->baseUrl(env('DROPINBLOG_API_URL', 'https://api.dropinblog.com') . '/v2/blog/' . $blogId . '/rendered');
    }

    private function fetchAndRenderView(string $endpoint, string $viewName, array $params = []): Renderable
    {
        $response = $this->getHttpClient()->get($endpoint, $params);

        if ($response->ok()) {
            $data = $response->object()->data;
            return view("dropinblog::{$viewName}", [
                'bodyHtml' => $data->body_html,
                'headHtml' => $data->head_html,
                'headItems' => $data->head_items,
                'isDropInBlog' => true
            ]);
        }

        abort($response->status());
    }

    private function fetchAndReturnResponse(string $endpoint, array $params = []): Response
    {
        $response = $this->getHttpClient()->get($endpoint, $params);

        if ($response->ok()) {
            $data = $response->object()->data;

            $content = $data->sitemap ?? $data->feed;
            $contentType = $data->content_type ?? 'application/xml; charset=utf-8';

            return response($content)->header('Content-Type', $contentType);
        }

        abort($response->status());
    }

    private function getFeedParams(): array
    {
        return [
            'type' => request()->input('type', config('dropinblog.feed.type')),
            'limit' => request()->input('limit', config('dropinblog.feed.limit'))
        ];
    }
}
