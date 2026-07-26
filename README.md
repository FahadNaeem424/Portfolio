# Fahad Naeem — Developer Portfolio

A complete, dependency-free portfolio built with PHP 8, semantic HTML5, CSS3, and vanilla JavaScript. It runs directly on XAMPP, Apache, LiteSpeed, or standard shared PHP hosting. No package installation, framework, database, router, or build step is required.

## Quick start with XAMPP

1. Copy the entire `Portfolio` folder into `C:\xampp\htdocs\`.
2. Start Apache from the XAMPP Control Panel.
3. Visit `http://localhost/Portfolio/`.

You can also run a local preview from this folder with PHP's development server:

```text
php -S localhost:8080
```

Then open `http://localhost:8080/`.

## First edits to make

Open `config.php` and replace the sample contact details and profile URLs. This one file controls:

- Name, title, introduction, email, phone, location, and availability
- Résumé link
- Accent colour
- Dark or light default theme
- Whether visitors can switch themes
- LinkedIn, GitHub, Fiverr, Upwork, and other links
- Contact-form recipient and subject prefix
- SEO description, keywords, base URL, and social preview image

Set `base_url` to the final domain before launch, for example:

```php
'base_url' => 'https://fahadnaeem.dev',
```

The included `assets/docs/resume.html` is a working editable sample. Replace it with your final PDF and update `resume_url` in `config.php` when ready.

## Editing portfolio content

- `data/sections.php` contains About, Statistics, Skills, Experience, Services, Education, Volunteer Work, Achievements, Certifications, and Testimonials.
- `data/projects.php` contains all project content, filters, screenshots, links, technologies, challenges, solutions, and results.
- Project pages live in `projects/`. Each one sets a slug and reuses the shared detail template.

To add a project:

1. Copy an existing project entry in `data/projects.php` and give it a unique `slug`.
2. Add its cover and screenshot files under `assets/images/projects/`.
3. Copy an existing file in `projects/`, rename it to match the slug, and update `$project_slug`.

## Theme configuration

In `config.php`:

```php
'default_theme' => 'dark', // use dark or light
'enable_theme_switcher' => true,
'accent_colour' => '#6ee7b7',
```

The visitor's choice is stored only in their browser.

## Contact form

The form includes:

- Server-side validation and escaped output
- CSRF tokens
- Honeypot protection
- Minimum-submit-time protection
- Session rate limiting
- Email header-injection checks
- POST/Redirect/GET on success

PHP's `mail()` function must be configured on the server. Shared hosts commonly provide this automatically. XAMPP does not send mail until its PHP mail settings are connected to an SMTP service. If mail is unavailable, the form presents the visitor with a direct-email fallback.

For production:

1. Change `recipient_email` in `config.php`.
2. Confirm that the hosting provider supports PHP `mail()`.
3. Test a real submission after deployment.
4. Use HTTPS so the session cookie can be marked secure.

## SEO and deployment

- `sitemap.php` generates a sitemap from the project data.
- `robots.txt` allows public pages and hides internal content directories from crawlers.
- Open Graph, Twitter Card, canonical metadata, and Person/CreativeWork JSON-LD are generated automatically.
- The root `.htaccess` adds common security headers and cache rules on Apache-compatible hosting.
- Configure custom error pages in your hosting panel to point to `404.php` and `500.php` if the host does not pick them up automatically.

Before publishing, replace sample URLs, client quotes, education details, certificates, project links, and SVG interface placeholders with verified personal content.

## File structure

```text
Portfolio/
├── assets/
│   ├── css/styles.css
│   ├── docs/resume.html
│   ├── images/
│   └── js/main.js
├── data/
│   ├── projects.php
│   └── sections.php
├── includes/
│   ├── contact-handler.php
│   ├── footer.php
│   ├── functions.php
│   ├── header.php
│   ├── navigation.php
│   ├── project-card.php
│   ├── project-detail-template.php
│   └── social-links.php
├── projects/
│   └── one direct PHP file per project
├── 404.php
├── 500.php
├── config.php
├── index.php
├── robots.txt
└── sitemap.php
```
