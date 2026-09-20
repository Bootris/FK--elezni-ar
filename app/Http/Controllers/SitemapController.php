<?php

namespace App\Http\Controllers;

use App\Models\Post;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $posts = Post::published()->orderByDesc('published_at')->get();
        // Google ignores a lastmod that is always "now"; use the last real content change.
        $contentUpdated = ($posts->max('updated_at') ?? now())->toISOString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        foreach (['sr', 'en'] as $locale) {
            $xml .= $this->url("{$baseUrl}/{$locale}", $contentUpdated, 'weekly', '1.0', $this->alternates($baseUrl, ''));
            foreach (['omladinci' => '0.9', 'prvi-tim' => '0.8', 'podrzi-klub' => '0.7', 'kontakt' => '0.7'] as $page => $priority) {
                $xml .= $this->url("{$baseUrl}/{$locale}/{$page}", $contentUpdated, 'weekly', $priority, $this->alternates($baseUrl, "/{$page}"));
            }
        }

        $xml .= $this->url("{$baseUrl}/vesti", $contentUpdated, 'daily', '0.8');
        $xml .= $this->url("{$baseUrl}/video", $contentUpdated, 'weekly', '0.6');

        foreach ($posts as $post) {
            $xml .= $this->url(
                "{$baseUrl}/vesti/{$post->slug}",
                $post->updated_at->toISOString(),
                'monthly',
                '0.6',
            );
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * robots.txt is served here (not as a static file) so the Sitemap line can
     * carry the absolute URL crawlers require, taken from APP_URL.
     */
    public function robots()
    {
        $baseUrl = rtrim(config('app.url'), '/');

        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /api/',
            'Disallow: /livewire/',
            '',
            "Sitemap: {$baseUrl}/sitemap.xml",
            '',
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /** hreflang pairs for a locale-prefixed page (`$path` starts with "/" or is empty). */
    private function alternates(string $baseUrl, string $path): array
    {
        $alternates = [];
        foreach (['sr', 'en'] as $locale) {
            $alternates[$locale] = "{$baseUrl}/{$locale}{$path}";
        }
        $alternates['x-default'] = $alternates['sr'];

        return $alternates;
    }

    private function url(string $loc, string $lastmod, string $changefreq, string $priority, array $alternates = []): string
    {
        $xml = "  <url>\n"
            . '    <loc>' . htmlspecialchars($loc) . "</loc>\n"
            . "    <lastmod>{$lastmod}</lastmod>\n"
            . "    <changefreq>{$changefreq}</changefreq>\n"
            . "    <priority>{$priority}</priority>\n";

        foreach ($alternates as $hreflang => $href) {
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"{$hreflang}\" href=\"" . htmlspecialchars($href) . "\"/>\n";
        }

        return $xml . "  </url>\n";
    }
}
