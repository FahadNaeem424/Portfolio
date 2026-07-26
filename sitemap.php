<?php
declare(strict_types=1);

$page_depth = 0;
$config = require __DIR__ . '/config.php';
$projects = require __DIR__ . '/data/projects.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=UTF-8');
$urls = [
    ['loc' => site_url($config, 'index.php'), 'priority' => '1.0'],
];
foreach ($projects as $project) {
    $urls[] = ['loc' => site_url($config, 'projects/' . $project['slug'] . '.php'), 'priority' => $project['featured'] ? '0.9' : '0.8'];
}
echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
    <url>
        <loc><?= e($url['loc']) ?></loc>
        <changefreq>monthly</changefreq>
        <priority><?= e($url['priority']) ?></priority>
    </url>
<?php endforeach; ?>
</urlset>
