<?php
declare(strict_types=1);

/**
 * Main portfolio configuration.
 * Edit the values in this file to update your identity, links, theme, and contact settings.
 */
return [
    'site' => [
        'name' => 'Fahad Naeem',
        'title' => 'Software Engineer · Full-Stack Web Developer · .NET Desktop Developer',
        'short_title' => 'Software Engineer',
        'introduction' => 'I build dependable web platforms, REST APIs, and polished Windows desktop applications that turn complex workflows into simple, useful software.',
        'email' => 'fahad@example.com',
        'phone' => '+92 300 1234567',
        'location' => 'Karachi, Pakistan',
        'availability' => 'Available for freelance and remote opportunities',
        'resume_url' => 'assets/docs/resume.html',
        'base_url' => '', // Example: https://fahadnaeem.dev (leave blank for automatic local URLs)
        'accent_colour' => '#6ee7b7',
        'default_theme' => 'dark', // dark or light
        'enable_theme_switcher' => true,
        'copyright_start_year' => 2024,
    ],
    'professional_links' => [
        'github' => ['label' => 'GitHub', 'url' => 'https://github.com/fahadnaeem', 'icon' => 'GH'],
        'linkedin' => ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/fahadnaeem', 'icon' => 'in'],
        'fiverr' => ['label' => 'Fiverr', 'url' => 'https://www.fiverr.com/fahadnaeem', 'icon' => 'fi'],
        'upwork' => ['label' => 'Upwork', 'url' => 'https://www.upwork.com/freelancers/', 'icon' => 'up'],
        'email' => ['label' => 'Email', 'url' => 'mailto:fahad@example.com', 'icon' => '@'],
    ],
    'contact' => [
        'recipient_email' => 'fahad@example.com',
        'subject_prefix' => '[Portfolio]',
        'success_message' => 'Thanks — your message has been sent. I will get back to you soon.',
        'minimum_submit_seconds' => 2,
        'rate_limit_seconds' => 20,
    ],
    'seo' => [
        'description' => 'Portfolio of Fahad Naeem, a software engineer specialising in full-stack web development, .NET desktop applications, REST APIs, PHP, C#, WPF, and ASP.NET.',
        'keywords' => 'Fahad Naeem, software engineer, full-stack developer, .NET developer, C#, WPF, PHP, ASP.NET, Karachi',
        'og_image' => 'assets/images/og-cover.svg',
        'twitter_handle' => '',
    ],
];
