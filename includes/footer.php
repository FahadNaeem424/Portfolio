<?php
declare(strict_types=1);

$currentYear = (int) date('Y');
$startYear = (int) $config['site']['copyright_start_year'];
$yearLabel = $startYear < $currentYear ? $startYear . '–' . $currentYear : (string) $currentYear;
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <a class="brand footer-brand" href="<?= e(site_path('index.php')) ?>">
                <span class="brand-mark" aria-hidden="true">FN</span>
                <span class="brand-copy"><strong><?= e($config['site']['name']) ?></strong><small>Software Engineer</small></span>
            </a>
            <p>Building clear, dependable software for the web and Windows.</p>
        </div>
        <div class="footer-links">
            <strong>Navigate</strong>
            <a href="<?= e(site_path('index.php#about')) ?>">About</a>
            <a href="<?= e(site_path('index.php#projects')) ?>">Projects</a>
            <a href="<?= e(site_path('index.php#services')) ?>">Services</a>
            <a href="<?= e(site_path('index.php#contact')) ?>">Contact</a>
        </div>
        <div class="footer-contact">
            <strong>Connect</strong>
            <a href="mailto:<?= e($config['site']['email']) ?>"><?= e($config['site']['email']) ?></a>
            <span><?= e($config['site']['location']) ?></span>
            <?php $social_class = 'footer-socials'; require __DIR__ . '/social-links.php'; ?>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>© <?= e($yearLabel) ?> <?= e($config['site']['name']) ?>. All rights reserved.</p>
        <a href="#main-content">Back to top ↑</a>
    </div>
</footer>
<script src="<?= e(site_path('assets/js/main.js')) ?>" defer></script>
</body>
</html>
