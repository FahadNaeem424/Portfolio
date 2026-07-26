<?php
declare(strict_types=1);

session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

$page_depth = 0;
$current_page = 'home';
$config = require __DIR__ . '/config.php';
$sections = require __DIR__ . '/data/sections.php';
$projects = require __DIR__ . '/data/projects.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/contact-handler.php';

$contact = process_contact_form($config);
if (isset($_GET['sent'], $_SESSION['contact_flash'])) {
    $contact = ['status' => 'success', 'message' => (string) $_SESSION['contact_flash'], 'errors' => [], 'old' => []];
    unset($_SESSION['contact_flash']);
}
$old = $contact['old'];
$categories = project_categories($projects);
$page_title = $config['site']['name'] . ' — Software Engineer & Full-Stack Developer';
$page_description = $config['seo']['description'];
$canonical_url = site_url($config, 'index.php');
require __DIR__ . '/includes/header.php';
?>
<main id="main-content">
    <section class="hero section" id="home" aria-labelledby="hero-heading">
        <div class="hero-grid container">
            <div class="hero-copy reveal">
                <p class="availability"><span aria-hidden="true"></span><?= e($config['site']['availability']) ?></p>
                <p class="eyebrow">Hello, I’m <?= e($config['site']['name']) ?>.</p>
                <h1 id="hero-heading">I engineer <span>useful software</span> for web and Windows.</h1>
                <p class="hero-intro"><?= e($config['site']['introduction']) ?></p>
                <div class="hero-actions">
                    <a class="button button-primary" href="#projects">Explore my work <span aria-hidden="true">↓</span></a>
                    <a class="button button-secondary" href="<?= e($config['site']['resume_url']) ?>" target="_blank" rel="noopener">View résumé <span aria-hidden="true">↗</span></a>
                </div>
                <?php $social_class = 'hero-socials'; require __DIR__ . '/includes/social-links.php'; ?>
            </div>
            <div class="hero-visual reveal" aria-label="Developer profile summary">
                <div class="code-window">
                    <div class="window-bar" aria-hidden="true"><span></span><span></span><span></span><small>fahad.profile</small></div>
                    <div class="code-content" aria-hidden="true">
                        <p><i>01</i><span class="code-purple">const</span> developer = {</p>
                        <p><i>02</i>&nbsp;&nbsp;name: <span class="code-green">'Fahad Naeem'</span>,</p>
                        <p><i>03</i>&nbsp;&nbsp;roles: [</p>
                        <p><i>04</i>&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-green">'Software Engineer'</span>,</p>
                        <p><i>05</i>&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-green">'Full-Stack Developer'</span>,</p>
                        <p><i>06</i>&nbsp;&nbsp;&nbsp;&nbsp;<span class="code-green">'.NET Desktop Developer'</span></p>
                        <p><i>07</i>&nbsp;&nbsp;],</p>
                        <p><i>08</i>&nbsp;&nbsp;ships: <span class="code-green">'reliable products'</span>,</p>
                        <p><i>09</i>&nbsp;&nbsp;available: <span class="code-orange">true</span></p>
                        <p><i>10</i>};</p>
                    </div>
                    <div class="terminal-line"><span>›</span> Building with clarity<span class="cursor" aria-hidden="true"></span></div>
                </div>
                <div class="floating-tech floating-tech-one">C# / .NET</div>
                <div class="floating-tech floating-tech-two">PHP + MySQL</div>
                <div class="floating-tech floating-tech-three">REST APIs</div>
            </div>
        </div>
        <div class="tech-marquee" aria-label="Core technology list">
            <div>
                <?php foreach (['C#', '.NET', 'WPF', 'ASP.NET', 'PHP 8', 'MySQL', 'SQL Server', 'JavaScript', 'REST APIs', 'WooCommerce'] as $technology): ?>
                    <span><?= e($technology) ?></span><b aria-hidden="true">◆</b>
                <?php endforeach; ?>
                <?php foreach (['C#', '.NET', 'WPF', 'ASP.NET', 'PHP 8', 'MySQL', 'SQL Server', 'JavaScript', 'REST APIs', 'WooCommerce'] as $technology): ?>
                    <span aria-hidden="true"><?= e($technology) ?></span><b aria-hidden="true">◆</b>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section about-section" id="about" aria-labelledby="about-heading">
        <div class="container about-grid">
            <div class="section-intro reveal">
                <p class="eyebrow"><?= e($sections['about']['eyebrow']) ?></p>
                <h2 id="about-heading"><?= e($sections['about']['heading']) ?></h2>
            </div>
            <div class="about-copy reveal">
                <?php foreach ($sections['about']['paragraphs'] as $paragraph): ?>
                    <p><?= e($paragraph) ?></p>
                <?php endforeach; ?>
                <ul class="check-list">
                    <?php foreach ($sections['about']['highlights'] as $highlight): ?>
                        <li><span aria-hidden="true">✓</span><?= e($highlight) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <section class="container stats-grid" id="statistics" aria-labelledby="statistics-heading">
            <h2 class="sr-only" id="statistics-heading">Professional statistics</h2>
            <?php foreach ($sections['statistics'] as $stat): ?>
                <div class="stat-card reveal">
                    <strong><?= e($stat['value']) ?></strong>
                    <span><?= e($stat['label']) ?></span>
                </div>
            <?php endforeach; ?>
        </section>
    </section>

    <section class="section surface-section" id="skills" aria-labelledby="skills-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div>
                    <p class="eyebrow">Technical toolkit</p>
                    <h2 id="skills-heading">Built across the stack.</h2>
                </div>
                <p>Tools selected for dependable delivery—not trends for their own sake.</p>
            </div>
            <div class="skill-grid">
                <?php foreach ($sections['skills'] as $skillGroup): ?>
                    <article class="skill-card reveal">
                        <div class="skill-icon" aria-hidden="true">&lt;/&gt;</div>
                        <h3><?= e($skillGroup['group']) ?></h3>
                        <p><?= e($skillGroup['description']) ?></p>
                        <div class="tag-list">
                            <?php foreach ($skillGroup['items'] as $skill): ?><span><?= e($skill) ?></span><?php endforeach; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="experience" aria-labelledby="experience-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div><p class="eyebrow">Professional journey</p><h2 id="experience-heading">Experience that ships.</h2></div>
                <p>Practical engineering across client delivery, product thinking, and operational support.</p>
            </div>
            <div class="timeline">
                <?php foreach ($sections['experience'] as $experience): ?>
                    <article class="timeline-item reveal">
                        <div class="timeline-marker" aria-hidden="true"></div>
                        <p class="timeline-period"><?= e($experience['period']) ?></p>
                        <div class="timeline-content">
                            <h3><?= e($experience['role']) ?></h3>
                            <p class="timeline-company"><?= e($experience['company']) ?></p>
                            <p><?= e($experience['summary']) ?></p>
                            <ul>
                                <?php foreach ($experience['achievements'] as $achievement): ?><li><?= e($achievement) ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section surface-section" id="services" aria-labelledby="services-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div><p class="eyebrow">What I do</p><h2 id="services-heading">Software services, end to end.</h2></div>
                <p>Focused technical delivery shaped around the workflow, users, and business outcome.</p>
            </div>
            <div class="services-grid">
                <?php foreach ($sections['services'] as $service): ?>
                    <article class="service-card reveal">
                        <span class="service-number"><?= e($service['number']) ?></span>
                        <h3><?= e($service['title']) ?></h3>
                        <p><?= e($service['description']) ?></p>
                        <ul>
                            <?php foreach ($service['deliverables'] as $deliverable): ?><li><?= e($deliverable) ?></li><?php endforeach; ?>
                        </ul>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section projects-section" id="projects" aria-labelledby="projects-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div><p class="eyebrow">Selected work</p><h2 id="projects-heading">Projects with purpose.</h2></div>
                <p>Web, desktop, booking, commerce, and operations products built around real use cases.</p>
            </div>
            <div class="project-toolbar reveal">
                <label class="project-search">
                    <span class="sr-only">Search projects</span>
                    <span aria-hidden="true">⌕</span>
                    <input type="search" placeholder="Search projects or technology…" autocomplete="off" data-project-search>
                </label>
                <div class="filter-list" role="group" aria-label="Filter projects by category">
                    <button class="filter-button active" type="button" data-project-filter="all" aria-pressed="true">All</button>
                    <button class="filter-button" type="button" data-project-filter="featured" aria-pressed="false">Featured</button>
                    <?php foreach ($categories as $category): ?>
                        <button class="filter-button" type="button" data-project-filter="<?= e(strtolower($category)) ?>" aria-pressed="false"><?= e($category) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="projects-grid" data-project-grid>
                <?php foreach ($projects as $project): require __DIR__ . '/includes/project-card.php'; endforeach; ?>
            </div>
            <p class="empty-projects" hidden data-project-empty>No projects match that search yet.</p>
        </div>
    </section>

    <section class="section surface-section" id="education" aria-labelledby="education-heading">
        <div class="container two-column-section">
            <div class="section-intro reveal">
                <p class="eyebrow">Foundation</p>
                <h2 id="education-heading">Education & continuous learning.</h2>
            </div>
            <div class="stacked-cards">
                <?php foreach ($sections['education'] as $education): ?>
                    <article class="detail-card reveal">
                        <p class="meta"><?= e($education['period']) ?></p>
                        <h3><?= e($education['qualification']) ?></h3>
                        <p class="accent-copy"><?= e($education['institution']) ?></p>
                        <p><?= e($education['detail']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="volunteer" aria-labelledby="volunteer-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div><p class="eyebrow">Beyond client work</p><h2 id="volunteer-heading">Volunteer work & community.</h2></div>
            </div>
            <div class="two-card-grid">
                <?php foreach ($sections['volunteer'] as $item): ?>
                    <article class="detail-card reveal">
                        <p class="meta"><?= e($item['period']) ?></p>
                        <h3><?= e($item['role']) ?></h3>
                        <p class="accent-copy"><?= e($item['organisation']) ?></p>
                        <p><?= e($item['description']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section surface-section" id="achievements" aria-labelledby="achievements-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div><p class="eyebrow">Highlights</p><h2 id="achievements-heading">Achievements that reflect how I work.</h2></div>
            </div>
            <div class="achievement-grid">
                <?php foreach ($sections['achievements'] as $index => $achievement): ?>
                    <article class="achievement-card reveal">
                        <span aria-hidden="true"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                        <h3><?= e($achievement['title']) ?></h3>
                        <p><?= e($achievement['detail']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="certifications" aria-labelledby="certifications-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div><p class="eyebrow">Credentials</p><h2 id="certifications-heading">Certifications & development.</h2></div>
            </div>
            <div class="certification-list">
                <?php foreach ($sections['certifications'] as $certificate): ?>
                    <a class="certificate-row reveal" href="<?= e($certificate['url']) ?>">
                        <span class="certificate-icon" aria-hidden="true">✓</span>
                        <span><strong><?= e($certificate['title']) ?></strong><small><?= e($certificate['issuer']) ?></small></span>
                        <span class="certificate-year"><?= e($certificate['year']) ?></span>
                        <span aria-hidden="true">↗</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section testimonials-section" id="testimonials" aria-labelledby="testimonials-heading">
        <div class="container">
            <div class="section-heading reveal">
                <div><p class="eyebrow">Kind words</p><h2 id="testimonials-heading">Trusted to make complexity manageable.</h2></div>
            </div>
            <div class="testimonial-slider reveal" data-testimonial-slider>
                <div class="testimonial-track">
                    <?php foreach ($sections['testimonials'] as $index => $testimonial): ?>
                        <figure class="testimonial-card<?= $index === 0 ? ' active' : '' ?>" data-testimonial aria-hidden="<?= $index === 0 ? 'false' : 'true' ?>">
                            <div class="quote-mark" aria-hidden="true">“</div>
                            <blockquote><?= e($testimonial['quote']) ?></blockquote>
                            <figcaption><strong><?= e($testimonial['name']) ?></strong><span><?= e($testimonial['role']) ?></span></figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
                <div class="slider-controls">
                    <button class="icon-button" type="button" data-testimonial-prev aria-label="Previous testimonial">←</button>
                    <div class="slider-dots" data-testimonial-dots></div>
                    <button class="icon-button" type="button" data-testimonial-next aria-label="Next testimonial">→</button>
                </div>
            </div>
        </div>
    </section>

    <section class="section contact-section" id="contact" aria-labelledby="contact-heading">
        <div class="container contact-grid">
            <div class="contact-copy reveal">
                <p class="eyebrow">Start a conversation</p>
                <h2 id="contact-heading">Have a useful product in mind?</h2>
                <p>Tell me what you are building, what is getting in the way, or where you need an experienced pair of hands.</p>
                <div class="contact-details">
                    <a href="mailto:<?= e($config['site']['email']) ?>"><span aria-hidden="true">@</span><span><small>Email</small><?= e($config['site']['email']) ?></span></a>
                    <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $config['site']['phone']) ?? '') ?>"><span aria-hidden="true">☎</span><span><small>Phone</small><?= e($config['site']['phone']) ?></span></a>
                    <div><span aria-hidden="true">⌖</span><span><small>Location</small><?= e($config['site']['location']) ?></span></div>
                </div>
                <?php $social_class = 'contact-socials'; require __DIR__ . '/includes/social-links.php'; ?>
            </div>

            <form class="contact-form reveal" method="post" action="index.php#contact" novalidate data-contact-form>
                <input type="hidden" name="form_name" value="contact">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="form_started_at" value="<?= e((string) time()) ?>">
                <div class="honeypot" aria-hidden="true">
                    <label for="website">Website</label><input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>

                <?php if ($contact['status'] !== null): ?>
                    <div class="form-alert <?= e($contact['status']) ?>" role="<?= $contact['status'] === 'error' ? 'alert' : 'status' ?>">
                        <strong><?= e($contact['message']) ?></strong>
                        <?php if ($contact['errors'] !== []): ?><ul><?php foreach ($contact['errors'] as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul><?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="form-row">
                    <label>Full name<input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" autocomplete="name" minlength="2" maxlength="80" required></label>
                    <label>Email address<input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" autocomplete="email" maxlength="160" required></label>
                </div>
                <label>Subject<input type="text" name="subject" value="<?= e($old['subject'] ?? '') ?>" minlength="3" maxlength="120" required></label>
                <label>Project details<textarea name="message" rows="6" minlength="20" maxlength="5000" placeholder="A little about your project, timeline, and goals…" required><?= e($old['message'] ?? '') ?></textarea></label>
                <button class="button button-primary submit-button" type="submit">Send message <span aria-hidden="true">↗</span></button>
                <p class="form-note">Your details are used only to respond to this enquiry.</p>
            </form>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
