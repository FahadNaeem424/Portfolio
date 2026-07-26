<?php
declare(strict_types=1);

$searchText = strtolower(implode(' ', array_merge(
    [$project['title'], $project['subtitle'], $project['summary']],
    $project['categories'],
    $project['technologies']
)));
?>
<article class="project-card reveal" data-project-card data-categories="<?= e(strtolower(implode('|', $project['categories']))) ?>" data-search="<?= e($searchText) ?>">
    <a class="project-image" href="<?= e(site_path('projects/' . $project['slug'] . '.php')) ?>" aria-label="View <?= e($project['title']) ?> case study">
        <img src="<?= e(site_path($project['cover'])) ?>" alt="" loading="lazy" width="1200" height="760">
        <span class="project-year"><?= e($project['year']) ?></span>
        <?php if ($project['featured']): ?><span class="featured-badge">Featured</span><?php endif; ?>
    </a>
    <div class="project-card-body">
        <div class="project-card-heading">
            <div>
                <p class="project-kicker"><?= e(implode(' · ', $project['categories'])) ?></p>
                <h3><a href="<?= e(site_path('projects/' . $project['slug'] . '.php')) ?>"><?= e($project['title']) ?></a></h3>
            </div>
            <a class="arrow-link" href="<?= e(site_path('projects/' . $project['slug'] . '.php')) ?>" aria-label="Open <?= e($project['title']) ?>">↗</a>
        </div>
        <p><?= e($project['summary']) ?></p>
        <div class="tag-list" aria-label="Technologies">
            <?php foreach (array_slice($project['technologies'], 0, 5) as $technology): ?>
                <span><?= e($technology) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</article>
