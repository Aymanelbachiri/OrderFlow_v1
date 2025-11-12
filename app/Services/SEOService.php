<?php

namespace App\Services;

use App\Models\SystemSetting;

class SEOService
{
    /**
     * Generate meta tags for a page
     */
    public static function generateMetaTags(array $data = []): array
    {
        $siteName = SystemSetting::get('site_name', 'IPTV Platform');
        $siteDescription = SystemSetting::get('site_description', 'Premium IPTV Services');
        
        $defaults = [
            'title' => $siteName,
            'description' => $siteDescription,
            'keywords' => 'IPTV, streaming, television, premium channels, VOD',
            'image' => asset('images/og-image.jpg'),
            'url' => request()->url(),
            'type' => 'website',
            'site_name' => $siteName,
        ];
        
        $meta = array_merge($defaults, $data);
        
        // Ensure title includes site name if not already present
        if (!str_contains($meta['title'], $siteName) && $meta['title'] !== $siteName) {
            $meta['title'] = $meta['title'] . ' - ' . $siteName;
        }
        
        return $meta;
    }

    /**
     * Generate structured data (JSON-LD)
     */
    public static function generateStructuredData(string $type, array $data = []): array
    {
        $siteName = SystemSetting::get('site_name', 'IPTV Platform');
        $siteUrl = config('app.url');
        
        $baseStructure = [
            '@context' => 'https://schema.org',
            '@type' => $type,
        ];
        
        switch ($type) {
            case 'Organization':
                return array_merge($baseStructure, [
                    'name' => $siteName,
                    'url' => $siteUrl,
                    'logo' => $siteUrl . '/images/logo.png',
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => SystemSetting::get('support_phone', ''),
                        'contactType' => 'customer service',
                        'email' => SystemSetting::get('contact_email', ''),
                    ],
                    'sameAs' => [
                        // Add social media URLs here
                    ],
                ], $data);
                
            case 'WebSite':
                return array_merge($baseStructure, [
                    'name' => $siteName,
                    'url' => $siteUrl,
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => $siteUrl . '/search?q={search_term_string}',
                        'query-input' => 'required name=search_term_string',
                    ],
                ], $data);
                
            case 'Article':
                return array_merge($baseStructure, [
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => $siteName,
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => $siteUrl . '/images/logo.png',
                        ],
                    ],
                ], $data);
                
            case 'Product':
                return array_merge($baseStructure, [
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $siteName,
                    ],
                ], $data);
                
            default:
                return array_merge($baseStructure, $data);
        }
    }

    /**
     * Generate breadcrumb structured data
     */
    public static function generateBreadcrumbs(array $breadcrumbs): array
    {
        $items = [];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'] ?? null,
            ];
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Optimize text for SEO
     */
    public static function optimizeText(string $text, int $maxLength = 160): string
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        
        $truncated = substr($text, 0, $maxLength - 3);
        $lastSpace = strrpos($truncated, ' ');
        
        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }
        
        return $truncated . '...';
    }

    /**
     * Generate canonical URL
     */
    public static function getCanonicalUrl(string $path = null): string
    {
        $baseUrl = rtrim(config('app.url'), '/');
        
        if ($path) {
            return $baseUrl . '/' . ltrim($path, '/');
        }
        
        return $baseUrl . request()->getPathInfo();
    }

    /**
     * Generate hreflang tags for multi-language support
     */
    public static function generateHreflangTags(array $languages = []): array
    {
        $tags = [];
        $currentUrl = request()->url();
        
        foreach ($languages as $lang => $url) {
            $tags[] = [
                'rel' => 'alternate',
                'hreflang' => $lang,
                'href' => $url,
            ];
        }
        
        // Add x-default
        if (!empty($languages)) {
            $tags[] = [
                'rel' => 'alternate',
                'hreflang' => 'x-default',
                'href' => $currentUrl,
            ];
        }
        
        return $tags;
    }

    /**
     * Check if content is SEO optimized
     */
    public static function analyzeSEO(array $content): array
    {
        $issues = [];
        $score = 100;
        
        // Title analysis
        if (empty($content['title'])) {
            $issues[] = 'Missing page title';
            $score -= 20;
        } elseif (strlen($content['title']) < 30) {
            $issues[] = 'Title too short (recommended: 30-60 characters)';
            $score -= 10;
        } elseif (strlen($content['title']) > 60) {
            $issues[] = 'Title too long (recommended: 30-60 characters)';
            $score -= 10;
        }
        
        // Description analysis
        if (empty($content['description'])) {
            $issues[] = 'Missing meta description';
            $score -= 20;
        } elseif (strlen($content['description']) < 120) {
            $issues[] = 'Meta description too short (recommended: 120-160 characters)';
            $score -= 10;
        } elseif (strlen($content['description']) > 160) {
            $issues[] = 'Meta description too long (recommended: 120-160 characters)';
            $score -= 10;
        }
        
        // Keywords analysis
        if (empty($content['keywords'])) {
            $issues[] = 'Missing keywords';
            $score -= 10;
        }
        
        // Content analysis
        if (isset($content['content'])) {
            $wordCount = str_word_count(strip_tags($content['content']));
            if ($wordCount < 300) {
                $issues[] = 'Content too short (recommended: 300+ words)';
                $score -= 15;
            }
            
            // Check for headings
            if (!preg_match('/<h[1-6]/', $content['content'])) {
                $issues[] = 'No headings found in content';
                $score -= 10;
            }
        }
        
        return [
            'score' => max(0, $score),
            'issues' => $issues,
            'status' => $score >= 80 ? 'good' : ($score >= 60 ? 'fair' : 'poor'),
        ];
    }

    /**
     * Generate Open Graph tags
     */
    public static function generateOpenGraphTags(array $data): array
    {
        $tags = [];
        
        $ogMapping = [
            'title' => 'og:title',
            'description' => 'og:description',
            'image' => 'og:image',
            'url' => 'og:url',
            'type' => 'og:type',
            'site_name' => 'og:site_name',
        ];
        
        foreach ($ogMapping as $key => $property) {
            if (isset($data[$key])) {
                $tags[$property] = $data[$key];
            }
        }
        
        return $tags;
    }

    /**
     * Generate Twitter Card tags
     */
    public static function generateTwitterCardTags(array $data): array
    {
        $tags = [
            'twitter:card' => 'summary_large_image',
        ];
        
        $twitterMapping = [
            'title' => 'twitter:title',
            'description' => 'twitter:description',
            'image' => 'twitter:image',
        ];
        
        foreach ($twitterMapping as $key => $property) {
            if (isset($data[$key])) {
                $tags[$property] = $data[$key];
            }
        }
        
        return $tags;
    }
}
