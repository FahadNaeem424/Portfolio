<?php
declare(strict_types=1);

$socialClass = $social_class ?? '';
?>
<div class="social-links <?= e($socialClass) ?>" aria-label="Professional profiles">
    <?php foreach ($config['professional_links'] as $link): ?>
        <a href="<?= e($link['url']) ?>"<?= external_link_attributes($link['url']) ?> aria-label="<?= e($link['label']) ?>" title="<?= e($link['label']) ?>">
            <span aria-hidden="true"><?= e($link['icon']) ?></span>
        </a>
    <?php endforeach; ?>
</div>
