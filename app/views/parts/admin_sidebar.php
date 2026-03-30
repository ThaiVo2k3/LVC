<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$uri = trim($_SERVER['REQUEST_URI'], "/");
$segment = explode("/", $uri);
$current = $segment[1] ?? "";
?>

<!-- Hamburger (mobile) -->
<button
  id="menuToggle"
  onclick="toggleMenu()"
  class="lg:hidden fixed top-4 left-4 z-50 flex flex-col gap-1.5">
  <span class="block w-6 h-0.5 bg-gray-800"></span>
  <span class="block w-6 h-0.5 bg-gray-800"></span>
  <span class="block w-6 h-0.5 bg-gray-800"></span>
</button>

<!-- Overlay -->
<div
  id="sidebarOverlay"
  onclick="toggleMenu()"
  class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

<!-- Sidebar -->
<aside
  id="sidebar"
  class="
    fixed top-0 left-0 z-50
    w-64 h-screen
    bg-white border-r
    transform -translate-x-full
    transition-transform duration-300
    lg:translate-x-0
  ">
  <nav class="h-full overflow-y-auto px-4 py-6 text-sm">

    <!-- DASHBOARD -->
    <h3 class="text-xs font-semibold text-gray-400 mb-2">DASHBOARD</h3>
    <ul class="space-y-1 mb-4">
      <li>
        <a href="/admin/dashboard"
          class="flex items-center gap-3 px-3 py-2 rounded
           <?= $current === 'dashboard' ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100' ?>">
          <i class="fa-solid fa-chart-line"></i>
          Tổng quan
        </a>
      </li>
    </ul>

    <!-- SẢN PHẨM -->
    <h3 class="text-xs font-semibold text-gray-400 mb-2">QUẢN LÝ SẢN PHẨM</h3>
    <ul class="space-y-1 mb-4">
      <li>
        <a href="/admin/brands"
          class="flex items-center gap-3 px-3 py-2 rounded
           <?= $current === 'brands' ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100' ?>">
          <i class="fa-solid fa-layer-group"></i>
          Hãng
        </a>
      </li>

      <li>
        <a href="/admin/categories"
          class="flex items-center gap-3 px-3 py-2 rounded
           <?= $current === 'categories' ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100' ?>">
          <i class="fa-solid fa-list"></i>
          Danh mục
        </a>
      </li>
      <li>
        <a href="/admin/products"
          class="flex items-center gap-3 px-3 py-2 rounded
           <?= $current === 'products' ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100' ?>">
          <i class="fa-solid fa-box"></i>
          Sản phẩm
        </a>
      </li>
    </ul>

    <!-- ĐƠN HÀNG -->
    <h3 class="text-xs font-semibold text-gray-400 mb-2">ĐƠN HÀNG</h3>
    <ul class="space-y-1 mb-4">
      <li>
        <a href="/admin/orders"
          class="flex items-center gap-3 px-3 py-2 rounded
           <?= $current === 'orders' ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100' ?>">
          <i class="fa-solid fa-receipt"></i>
          Đơn hàng
        </a>
      </li>
    </ul>

    <!-- KHÁCH HÀNG -->
    <h3 class="text-xs font-semibold text-gray-400 mb-2">KHÁCH HÀNG</h3>
    <ul class="space-y-1 mb-4">
      <li>
        <a href="/admin/users"
          class="flex items-center gap-3 px-3 py-2 rounded
           <?= $current === 'users' ? 'bg-blue-100 text-blue-600 font-semibold' : 'hover:bg-gray-100' ?>">
          <i class="fa-solid fa-users"></i>
          Danh sách khách hàng
        </a>
      </li>
    </ul>

    <!-- TÀI KHOẢN -->
    <h3 class="text-xs font-semibold text-gray-400 mb-2">TÀI KHOẢN</h3>
    <ul class="space-y-1">
      <li>
        <a href="/logout"
          class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-50 text-red-600">
          <i class="fa-solid fa-right-from-bracket"></i>
          Đăng xuất
        </a>
      </li>
    </ul>

  </nav>
</aside>
<script>
  function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
  }
</script>