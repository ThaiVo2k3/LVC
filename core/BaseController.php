<?php
class BaseController
{
    // public function __construct()
    // {
    //     if (session_status() === PHP_SESSION_NONE) {
    //         session_start();
    //     }
    // }
    protected function renderView($view, $data = [])
    {
        $layout = $data['layout'];
        unset($data['layout']);

        extract($data);

        $content = "./app/views/{$view}.php";

        require_once "./app/views/layouts/{$layout}.php";
    }
}
