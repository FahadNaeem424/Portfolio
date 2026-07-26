<?php
declare(strict_types=1);

if (!isset($config)) {
    $requestPath = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $page_depth = max(0, substr_count($requestPath, '/'));
    $config = require __DIR__ . '/config.php';
    require_once __DIR__ . '/includes/functions.php';
}
$current_page = 'error';
$page_title = 'Page not found — ' . $config['site']['name'];
$page_description = 'The requested portfolio page could not be found.';
http_response_code(404);
require __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="error-page">
    <div class="container">
        <p class="error-code" aria-hidden="true">404</p>
        <p class="eyebrow" style="justify-content:center">Page not found</p>
        <h1>That page is not part of this build.</h1>
        <p>The link may be outdated, or the address may have been typed incorrectly.</p>
        <a class="button button-primary" href="<?= e(site_path('index.php')) ?>">Return to the portfolio</a>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
