<?php
declare(strict_types=1);

if (!isset($config)) {
    $requestPath = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $page_depth = max(0, substr_count($requestPath, '/'));
    $config = require __DIR__ . '/config.php';
    require_once __DIR__ . '/includes/functions.php';
}
$current_page = 'error';
$page_title = 'Server error — ' . $config['site']['name'];
$page_description = 'The portfolio encountered a temporary server error.';
http_response_code(500);
require __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="error-page">
    <div class="container">
        <p class="error-code" aria-hidden="true">500</p>
        <p class="eyebrow" style="justify-content:center">Temporary problem</p>
        <h1>The server could not complete that request.</h1>
        <p>Please try again in a moment, or contact me directly by email.</p>
        <div class="hero-actions" style="justify-content:center">
            <a class="button button-primary" href="<?= e(site_path('index.php')) ?>">Return home</a>
            <a class="button button-secondary" href="mailto:<?= e($config['site']['email']) ?>">Email <?= e($config['site']['name']) ?></a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
