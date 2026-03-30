<?php
$cart_count = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += (int)$qty;
    }
}

$user_name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
?>
<header class="bg-white shadow-sm sticky-top">
    <div class="container d-flex align-items-center justify-content-between py-3">

        <!-- Logo -->
        <a href="/" class="d-flex align-items-center gap-2">
            <img src="/public/uploads/logo.jpg" class="h-10" alt="Logo">
        </a>

        <!-- Menu -->
        <nav class="d-none d-lg-block">
            <ul class="d-flex gap-4 list-unstyled m-0">
                <li><a href="/" class="text-dark text-decoration-none hover:text-blue-500">Trang chủ</a></li>
                <li><a href="/camera-quan-sat" class="text-dark text-decoration-none hover:text-blue-500">sản phẩm</a></li>
                <li><a href="#" class="text-dark text-decoration-none hover:text-blue-500">Giới thiệu</a></li>
                <li><a href="#" class="text-dark text-decoration-none hover:text-blue-500">Liên hệ</a></li>
            </ul>
        </nav>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3">

            <!-- Search -->
            <form method="GET" class="d-flex">
                <input type="search" name="search"
                    class="form-control rounded-start-pill"
                    placeholder="Tìm kiếm...">
                <button class="btn btn-primary rounded-end-pill">🔍</button>
            </form>

            <!-- Cart -->
            <a href="/cart" class="position-relative text-dark text-xl">
                🛒
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo (int)$cart_count; ?>
                </span>
            </a>

            <div>
                <?php if ($user_name): ?>
                    <?php
                    $redirectUrl = ($_SESSION['user']['role'] === 'admin')
                        ? '/admin/dashboard'
                        : '/';
                    ?>
                    <a href="<?= $redirectUrl ?>" class="fw-semibold text-decoration-none">
                        <?= $user_name; ?>
                    </a>

                    <a href="/logout" class="btn btn-outline-danger btn-sm ms-2">Đăng xuất</a>
                <?php else: ?>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký</button>
                <?php endif; ?>
            </div>

        </div>
    </div>
</header>