<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$uri = trim($_SERVER['REQUEST_URI'], "/");
$segment = explode("/", $uri);
$current = $segment[0] ?? "";
?>

<aside class="sidebar">
  <ul class="sidebar-menu">

    <li>
      <a href="/profile" class="<?= $current === 'profile' ? 'active' : '' ?>">
        <i class="fa-solid fa-user"></i>
        Thông tin cá nhân
      </a>
    </li>

    <li>
      <a href="/orders" class="<?= $current === 'orders' ? 'active' : '' ?>">
        <i class="fa-solid fa-receipt"></i>
        Đơn hàng
      </a>
    </li>

    <li>
      <a href="/logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        Đăng xuất
      </a>
    </li>

  </ul>
</aside>