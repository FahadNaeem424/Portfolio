<?php
declare(strict_types=1);

$pageTitle = $page_title ?? $config['site']['name'] . ' — ' . $config['site']['short_title'];
$pageDescription = $page_description ?? $config['seo']['description'];
$pageImage = $page_image ?? $config['seo']['og_image'];
$canonicalUrl = $canonical_url ?? current_url($config);
$theme = (string) ($config['site']['default_theme'] ?? 'dark');
if (!is_valid_theme($theme)) {
    $theme = 'dark';
}
$accentColour = (string) ($config['site']['accent_colour'] ?? '#6ee7b7');
if (preg_match('/^#[0-9a-fA-F]{6}$/', $accentColour) !== 1) {
    $accentColour = '#6ee7b7';
}
$personSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $config['site']['name'],
    'jobTitle' => $config['site']['short_title'],
    'email' => $config['site']['email'],
    'telephone' => $config['site']['phone'],
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => $config['site']['location'],
    ],
    'url' => site_url($config),
    'sameAs' => array_values(array_filter(array_map(
        static fn (array $link): string => str_starts_with($link['url'], 'http') ? $link['url'] : '',
        $config['professional_links']
    ))),
    'knowsAbout' => ['C#', '.NET', 'WPF', 'ASP.NET', 'PHP', 'MySQL', 'SQL Server', 'JavaScript', 'REST APIs', 'WooCommerce'],
];
?>
<!doctype html>
<html lang="en" data-theme="<?= e($theme) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="keywords" content="<?= e($config['seo']['keywords']) ?>">
    <meta name="author" content="<?= e($config['site']['name']) ?>">
    <meta name="theme-color" content="<?= e($accentColour) ?>">
    <meta name="color-scheme" content="dark light">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">

    <meta property="og:type" content="<?= isset($project) ? 'article' : 'website' ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e(site_url($config, $pageImage)) ?>">
    <meta property="og:site_name" content="<?= e($config['site']['name']) ?> Portfolio">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($pageDescription) ?>">
    <meta name="twitter:image" content="<?= e(site_url($config, $pageImage)) ?>">
    <?php if ($config['seo']['twitter_handle'] !== ''): ?>
        <meta name="twitter:creator" content="<?= e($config['seo']['twitter_handle']) ?>">
    <?php endif; ?>

    <title><?= e($pageTitle) ?></title>
    <link rel="icon" href="<?= e(site_path('assets/images/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= e(site_path('assets/css/styles.css')) ?>">
    <style>:root{--accent:<?= e($accentColour) ?>}</style>
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('portfolio-theme');
                if (saved === 'dark' || saved === 'light') document.documentElement.dataset.theme = saved;
            } catch (error) {}
        }());
    </script>
    <script type="application/ld+json"><?= json_ld($personSchema) ?></script>
</head>
<body class="<?= isset($project) ? 'project-page' : 'home-page' ?>">
    <a class="skip-link" href="#main-content">Skip to content</a>
    <?php require __DIR__ . '/navigation.php'; ?>
