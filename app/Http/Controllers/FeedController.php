<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    /**
     * Generate an RSS feed for articles.
     */
    public function feed(): Response
    {
        $artikels = Artikel::with(['user', 'category'])->latest()->take(20)->get();

        $xml = view('feed.rss', compact('artikels'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8',
        ]);
    }

    /**
     * Generate an XML sitemap for articles.
     */
    public function sitemap(): Response
    {
        $artikels = Artikel::latest()->get();

        $xml = view('feed.sitemap', compact('artikels'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
