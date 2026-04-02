<!-- MOBILE BUTTON -->
<button id="toggleSidebar"
  class="d-md-none position-fixed top-0 start-0 mt-3 ms-3 btn btn-primary shadow"
  style="z-index:2000;">
  ☰
</button>

<!-- OVERLAY -->
<div id="overlay"
  class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none"
  style="z-index:1500;"></div>

<!-- SIDEBAR -->
<aside id="sidebar"
  class="bg-white border-end shadow-sm p-3 h-auto">

  <h5 class="fw-bold mb-3">👤 Tài khoản</h5>

  <?php
  $current = $_SERVER['REQUEST_URI'];
  ?>

  <nav class="nav flex-column gap-1">

    <a href="/profile"
      class="nav-link rounded px-3 py-2 sidebar-item 
    <?= ($current == '/profile') ? 'active-menu' : 'text-dark' ?>">
      <i class="fa fa-user me-2"></i> Thông tin
    </a>

    <a href="/orders"
      class="nav-link rounded px-3 py-2 sidebar-item 
    <?= (strpos($current, '/orders') === 0) ? 'active-menu' : 'text-dark' ?>">
      <i class="fa fa-cart-shopping me-2"></i> Đơn hàng
    </a>

    <a href="/logout"
      class="nav-link rounded px-3 py-2 sidebar-item-danger text-danger">
      <i class="fa fa-right-from-bracket me-2"></i> Đăng xuất
    </a>

  </nav>
</aside>

<style>
  .sidebar-item:hover {
    background: #f0f7ff;
    color: #0d6efd !important;
  }

  .sidebar-item-danger:hover {
    background: #ffe5e5;
  }

  .active-menu {
    background: #0d6efd;
    color: #fff !important;
    font-weight: 500;
  }

  /* MOBILE MODE */
  @media (max-width: 767px) {
    #sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100% !important;
      transform: translateX(-100%);
      transition: 0.3s;
      z-index: 1600;
    }

    #sidebar.active {
      transform: translateX(0);
    }
  }
</style>
<script>
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const btn = document.getElementById("toggleSidebar");

  btn.addEventListener("click", () => {
    sidebar.classList.add("active");
    overlay.classList.remove("d-none");

    // 👉 ẨN NÚT
    btn.classList.add("d-none");
  });

  overlay.addEventListener("click", () => {
    sidebar.classList.remove("active");
    overlay.classList.add("d-none");

    // 👉 HIỆN LẠI NÚT
    btn.classList.remove("d-none");
  });
</script>