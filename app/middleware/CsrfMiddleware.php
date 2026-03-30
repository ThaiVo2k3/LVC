<?php
class CsrfMiddleware
{
    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_token'] ?? '';
            $sessionToken = $_SESSION['_token'] ?? '';

            if (!$token || $token !== $sessionToken) {
                die("CSRF token mismatch!");
            }
        }
    }
}
