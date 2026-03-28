<?php
class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: /");
            exit;
        }
    }
}
