<?php
declare(strict_types=1);

function process_contact_form(array $config): array
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['form_name'] ?? '') !== 'contact') {
        return ['status' => null, 'message' => '', 'errors' => [], 'old' => []];
    }

    $old = [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'subject' => trim((string) ($_POST['subject'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];
    $old['name'] = trim(preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $old['name']) ?? '');
    $old['email'] = trim(preg_replace('/[\x00-\x20\x7F]+/u', '', $old['email']) ?? '');
    $old['subject'] = trim(preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $old['subject']) ?? '');
    $old['message'] = str_replace("\0", '', $old['message']);
    $errors = [];

    $sessionToken = (string) ($_SESSION['csrf_token'] ?? '');
    $postedToken = (string) ($_POST['csrf_token'] ?? '');
    if ($sessionToken === '' || $postedToken === '' || !hash_equals($sessionToken, $postedToken)) {
        $errors[] = 'Your session expired. Please refresh the page and try again.';
    }

    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        $errors[] = 'The message could not be processed.';
    }

    $startedAt = (int) ($_POST['form_started_at'] ?? 0);
    $minimumSeconds = max(0, (int) ($config['contact']['minimum_submit_seconds'] ?? 2));
    if ($startedAt <= 0 || time() - $startedAt < $minimumSeconds) {
        $errors[] = 'Please take a moment to review your message before sending.';
    }

    $lastSubmission = (int) ($_SESSION['last_contact_submission'] ?? 0);
    $rateLimit = max(0, (int) ($config['contact']['rate_limit_seconds'] ?? 20));
    if ($lastSubmission > 0 && time() - $lastSubmission < $rateLimit) {
        $errors[] = 'Please wait briefly before sending another message.';
    }

    if ($old['name'] === '' || text_length($old['name']) < 2 || text_length($old['name']) > 80) {
        $errors[] = 'Please enter a name between 2 and 80 characters.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || text_length($old['email']) > 160) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($old['subject'] === '' || text_length($old['subject']) < 3 || text_length($old['subject']) > 120) {
        $errors[] = 'Please enter a subject between 3 and 120 characters.';
    }
    if ($old['message'] === '' || text_length($old['message']) < 20 || text_length($old['message']) > 5000) {
        $errors[] = 'Please enter a message between 20 and 5,000 characters.';
    }
    if (preg_match('/[\r\n]/', $old['email']) === 1) {
        $errors[] = 'The email address contains invalid characters.';
    }

    if ($errors !== []) {
        return ['status' => 'error', 'message' => 'Please correct the highlighted form details.', 'errors' => $errors, 'old' => $old];
    }

    $recipient = (string) ($config['contact']['recipient_email'] ?? '');
    $prefix = (string) ($config['contact']['subject_prefix'] ?? '[Portfolio]');
    $safeSubject = preg_replace('/[\r\n]+/', ' ', $old['subject']) ?? 'Portfolio enquiry';
    $safeName = preg_replace('/[\r\n]+/', ' ', $old['name']) ?? 'Website visitor';
    $mailSubject = $prefix . ' ' . $safeSubject;
    $mailBody = "Name: {$safeName}\nEmail: {$old['email']}\n\nMessage:\n{$old['message']}\n";
    $host = preg_replace('/[^a-z0-9.-]/i', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    $fromDomain = $host !== '' && $host !== 'localhost' ? $host : 'localhost.localdomain';
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'From: Portfolio Website <no-reply@' . $fromDomain . '>',
        'Reply-To: ' . $safeName . ' <' . $old['email'] . '>',
        'X-Mailer: PHP/' . PHP_VERSION,
    ];

    $sent = $recipient !== '' && filter_var($recipient, FILTER_VALIDATE_EMAIL)
        && @mail($recipient, $mailSubject, $mailBody, implode("\r\n", $headers));

    if (!$sent) {
        return [
            'status' => 'error',
            'message' => 'The message could not be sent by this server. Please email me directly instead.',
            'errors' => [],
            'old' => $old,
        ];
    }

    $_SESSION['last_contact_submission'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['contact_flash'] = (string) ($config['contact']['success_message'] ?? 'Message sent.');
    header('Location: ' . site_path('index.php') . '?sent=1#contact', true, 303);
    exit;
}
