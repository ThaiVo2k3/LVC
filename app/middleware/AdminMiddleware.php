<?php
class AdminMiddleware
{
    public function handle()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập!";
            header("Location: /");
            exit;
        }
    }
}
