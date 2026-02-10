<?php
// GitHub Webhook - Auto-pull on push
// Set this same secret in your GitHub webhook configuration
$secret = '9794b0c7df6a9c973dd0f4b904d40c87f33574880d56a07ad4c3bac027338fb8';

$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input');

if (!$signature || !hash_equals('sha256=' . hash_hmac('sha256', $payload, $secret), $signature)) {
    http_response_code(403);
    echo 'Unauthorized';
    exit;
}

$data = json_decode($payload, true);
if (($data['ref'] ?? '') !== 'refs/heads/main') {
    http_response_code(200);
    echo 'Not main branch, skipping';
    exit;
}

$repo = '/home/jo7qy94ypdj1/public_html/hdsubmit.com';
$output = shell_exec("cd $repo && git pull origin main 2>&1");

http_response_code(200);
echo $output;
