@php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($pages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <priority>{{ $page['priority'] }}</priority>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
@isset($page['lastmod'])
        <lastmod>{{ $page['lastmod'] }}</lastmod>
@endisset
    </url>
@endforeach
</urlset>
