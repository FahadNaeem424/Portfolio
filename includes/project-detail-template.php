<?php
declare(strict_types=1);

$page_depth = 1;
$current_page = 'projects';
$config = require dirname(__DIR__) . '/config.php';
$projects = require dirname(__DIR__) . '/data/projects.php';
require_once __DIR__ . '/functions.php';

$project = project_by_slug($projects, (string) ($project_slug ?? ''));
if ($project === null) {
    http_response_code(404);
    require dirname(__DIR__) . '/404.php';
    exit;
}

$page_title = $project['title'] . ' — Project by ' . $config['site']['name'];
$page_description = $project['summary'];
$page_image = $project['cover'];
$canonical_url = site_url($config, 'projects/' . $project['slug'] . '.php');
$projectSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'CreativeWork',
    'name' => $project['title'],
    'description' => $project['summary'],
    'creator' => ['@type' => 'Person', 'name' => $config['site']['name']],
    'dateCreated' => $project['year'],
    'keywords' => implode(', ', $project['technologies']),
    'url' => $canonical_url,
];
require __DIR__ . '/header.php';
?>
<main id="main-content">
    <article class="project-detail">
        <header class="project-hero section">
            <div class="container">
                <a class="back-link" href="<?= e(site_path('index.php#projects')) ?>">← Back to all projects</a>
                <div class="project-hero-grid">
                    <div class="project-hero-copy reveal">
                        <p class="eyebrow"><?= e(implode(' · ', $project['categories'])) ?> · <?= e($project['year']) ?></p>
                        <h1><?= e($project['title']) ?></h1>
                        <p class="project-subtitle"><?= e($project['subtitle']) ?></p>
                        <p><?= e($project['summary']) ?></p>
                        <div class="project-links">
                            <?php
                            $linkLabels = ['live' => 'Live demo', 'github' => 'GitHub', 'documentation' => 'Documentation', 'video' => 'Video'];
                            foreach ($linkLabels as $type => $label):
                                if (($project['links'][$type] ?? '') === '') {
                                    continue;
                                }
                            ?>
                                <a class="button <?= $type === 'live' ? 'button-primary' : 'button-secondary' ?>" href="<?= e($project['links'][$type]) ?>" target="_blank" rel="noopener noreferrer"><?= e($label) ?> <span aria-hidden="true">↗</span></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="project-hero-image reveal">
                        <img src="<?= e(site_path($project['cover'])) ?>" alt="<?= e($project['title']) ?> interface preview" width="1200" height="760">
                    </div>
                </div>
            </div>
        </header>

        <nav class="project-subnav" aria-label="On this project page">
            <div class="container">
                <a href="#overview">Overview</a>
                <a href="#features">Features</a>
                <a href="#screenshots">Screenshots</a>
                <a href="#technologies">Technologies</a>
                <a href="#challenges">Challenges</a>
                <a href="#solutions">Solutions</a>
                <a href="#results">Results</a>
            </div>
        </nav>

        <div class="container project-story">
            <section class="project-story-section reveal" id="overview">
                <p class="eyebrow">01 · Overview</p>
                <h2>The product</h2>
                <p class="large-copy"><?= e($project['overview']) ?></p>
            </section>

            <section class="project-story-section reveal" id="features">
                <p class="eyebrow">02 · Features</p>
                <h2>What it does</h2>
                <div class="numbered-grid">
                    <?php foreach ($project['features'] as $index => $feature): ?>
                        <div><span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span><p><?= e($feature) ?></p></div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="project-story-section reveal" id="screenshots">
                <p class="eyebrow">03 · Screenshots</p>
                <h2>Interface views</h2>
                <div class="screenshot-grid">
                    <?php foreach ($project['screenshots'] as $screenshot): ?>
                        <a href="<?= e(site_path($screenshot['src'])) ?>" target="_blank" rel="noopener" class="screenshot-link">
                            <img src="<?= e(site_path($screenshot['src'])) ?>" alt="<?= e($screenshot['alt']) ?>" loading="lazy" width="1200" height="760">
                            <span>Open image ↗</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="project-story-section reveal" id="technologies">
                <p class="eyebrow">04 · Technologies</p>
                <h2>Engineering stack</h2>
                <div class="large-tag-list">
                    <?php foreach ($project['technologies'] as $technology): ?><span><?= e($technology) ?></span><?php endforeach; ?>
                </div>
            </section>

            <section class="project-story-section split-story reveal" id="challenges">
                <div><p class="eyebrow">05 · Challenges</p><h2>What needed care</h2></div>
                <ul class="story-list"><?php foreach ($project['challenges'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
            </section>

            <section class="project-story-section split-story reveal" id="solutions">
                <div><p class="eyebrow">06 · Solutions</p><h2>How I approached it</h2></div>
                <ul class="story-list"><?php foreach ($project['solutions'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
            </section>

            <section class="project-story-section result-section reveal" id="results">
                <p class="eyebrow">07 · Results</p>
                <h2>The outcome</h2>
                <div class="result-grid"><?php foreach ($project['results'] as $result): ?><div><span aria-hidden="true">✓</span><p><?= e($result) ?></p></div><?php endforeach; ?></div>
            </section>

            <aside class="next-project reveal">
                <?php
                $currentIndex = array_search($project, $projects, true);
                $nextProject = $projects[((int) $currentIndex + 1) % count($projects)];
                ?>
                <p class="eyebrow">Next case study</p>
                <a href="<?= e(site_path('projects/' . $nextProject['slug'] . '.php')) ?>">
                    <span><strong><?= e($nextProject['title']) ?></strong><small><?= e($nextProject['subtitle']) ?></small></span>
                    <span aria-hidden="true">→</span>
                </a>
            </aside>
        </div>
    </article>
    <script type="application/ld+json"><?= json_ld($projectSchema) ?></script>
</main>
<?php require __DIR__ . '/footer.php'; ?>
