<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

function site_path(string $path = ''): string
{
    $depth = $GLOBALS['page_depth'] ?? 0;
    return str_repeat('../', max(0, (int) $depth)) . ltrim($path, '/');
}

function site_url(array $config, string $path = ''): string
{
    $base = trim((string) ($config['site']['base_url'] ?? ''));
    if ($base !== '') {
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }

    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    if (($GLOBALS['page_depth'] ?? 0) > 0) {
        $scriptDir = str_replace('\\', '/', dirname($scriptDir));
    }
    $scriptDir = $scriptDir === '/' ? '' : rtrim($scriptDir, '/');

    return $scheme . '://' . $host . $scriptDir . '/' . ltrim($path, '/');
}

function current_url(array $config): string
{
    $path = ltrim((string) ($_SERVER['REQUEST_URI'] ?? ''), '/');
    return site_url($config, $path);
}

function active_page(string $page): string
{
    return ($GLOBALS['current_page'] ?? 'home') === $page ? ' aria-current="page"' : '';
}

function external_link_attributes(string $url): string
{
    return preg_match('/^https?:\/\//i', $url) === 1
        ? ' target="_blank" rel="noopener noreferrer"'
        : '';
}

function project_by_slug(array $projects, string $slug): ?array
{
    foreach ($projects as $project) {
        if (($project['slug'] ?? '') === $slug) {
            return $project;
        }
    }
    return null;
}

function project_categories(array $projects): array
{
    $categories = [];
    foreach ($projects as $project) {
        foreach ($project['categories'] ?? [] as $category) {
            $categories[$category] = true;
        }
    }
    $result = array_keys($categories);
    sort($result);
    return $result;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['csrf_token'];
}

function json_ld(array $data): string
{
    return json_encode(
        $data,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?: '{}';
}

function is_valid_theme(string $theme): bool
{
    return in_array($theme, ['dark', 'light'], true);
}
