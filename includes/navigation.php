<?php
declare(strict_types=1);

$homeHref = site_path('index.php');
$navItems = [
    'home' => 'Home',
    'about' => 'About',
    'skills' => 'Skills',
    'experience' => 'Experience',
    'services' => 'Services',
    'projects' => 'Projects',
    'contact' => 'Contact',
];
?>
<header class="site-header" data-site-header>
    <div class="container nav-shell">
        <a class="brand" href="<?= e($homeHref) ?>" aria-label="<?= e($config['site']['name']) ?> home">
            <span class="brand-mark" aria-hidden="true">FN</span>
            <span class="brand-copy">
                <strong><?= e($config['site']['name']) ?></strong>
                <small>Software Engineer</small>
            </span>
        </a>

        <button class="nav-toggle icon-button" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="primary-navigation" data-nav-toggle>
            <span></span><span></span><span></span>
        </button>

        <nav class="primary-nav" id="primary-navigation" aria-label="Primary navigation" data-navigation>
            <?php foreach ($navItems as $id => $label): ?>
                <a href="<?= e($homeHref . ($id === 'home' ? '#home' : '#' . $id)) ?>"<?= active_page($id) ?>><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="nav-actions">
            <?php if (!empty($config['site']['enable_theme_switcher'])): ?>
                <button class="theme-toggle icon-button" type="button" aria-label="Switch colour theme" title="Switch colour theme" data-theme-toggle>
                    <span class="theme-icon" aria-hidden="true">◐</span>
                </button>
            <?php endif; ?>
            <a class="button button-small button-primary nav-contact" href="<?= e($homeHref . '#contact') ?>">Let’s talk</a>
        </div>
    </div>
</header>
