<div class="container mt-5">

    <div class="hero-banner bg-dark text-white rounded-5 p-5 mb-5 text-center shadow-lg"
        style="background: url('/public/uploads/image.png') center/cover; min-height: 350px; display: flex; flex-direction: column; justify-content: center;">
        <h1 class="fw-bold display-4 mb-3 animate__animated animate__fadeInDown">Camera Chính Hãng</h1>
        <p class="fs-5 opacity-75 mb-4">Giám sát thông minh - An toàn tuyệt đối cho gia đình bạn</p>
        <div>
            <a href="#" class="btn btn-primary btn-lg px-5 rounded-pill shadow btn-hover">Mua ngay</a>
        </div>
    </div>

    <?php
    $sections = [
        ['title' => 'Sản phẩm mới', 'icon' => '🆕', 'color' => 'primary', 'data' => $top_new, 'btn' => 'outline-primary'],
        ['title' => 'Giá tốt', 'icon' => '💰', 'color' => 'success', 'data' => $top_cheap, 'btn' => 'success'],
        ['title' => 'Bán chạy', 'icon' => '🔥', 'color' => 'danger', 'data' => $top_sell, 'btn' => 'danger']
    ];
    foreach ($sections as $sec):
    ?>
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold border-start border-5 border-<?php echo $sec['color']; ?> ps-3 mb-0">
                    <?php echo $sec['icon'] . ' ' . $sec['title']; ?>
                </h2>
                <a href="#" class="text-decoration-none text-<?php echo $sec['color']; ?> fw-semibold">Xem tất cả →</a>
            </div>

            <div class="row g-4">
                <?php foreach ($sec['data'] as $p): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 product-card shadow-sm rounded-4 overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img src="/public/uploads/products/<?php echo $p['id']; ?>/<?php echo $p['image']; ?>"
                                    class="card-img-top" style="height:220px; object-fit:cover;">
                                <span class="position-absolute top-0 start-0 bg-<?php echo $sec['color']; ?> text-white px-3 py-1 m-2 rounded-pill small">
                                    Hot
                                </span>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold text-dark text-truncate mb-2"><?php echo $p['name']; ?></h6>
                                <p class="text-danger fw-bold fs-5 mb-3">
                                    <?php echo number_format($p['price']); ?> <u class="small">đ</u>
                                </p>
                                <a href="/product/<?php echo $p['id']; ?>"
                                    class="btn btn-<?php echo $sec['btn']; ?> mt-auto rounded-3 py-2 fw-semibold transition shadow-sm">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <section class="mb-5 py-4 bg-light rounded-5 px-4">
        <h2 class="fw-bold mb-5 text-center">Thương hiệu đối tác</h2>

        <div class="swiper brandSwiper pb-4">
            <div class="swiper-wrapper align-items-center">
                <?php foreach ($brands as $b): ?>
                    <div class="swiper-slide">
                        <div class="bg-white border rounded-4 p-3 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center hover:shadow-md transition">
                            <img src="/public/uploads/brands/<?php echo $b['id']; ?>/<?php echo $b['image']; ?>"
                                class="img-fluid mb-2"
                                style="height:60px; object-fit:contain; filter: grayscale(100%); transition: 0.3s;"
                                onmouseover="this.style.filter='grayscale(0%)'"
                                onmouseout="this.style.filter='grayscale(100%)'">
                            <p class="small fw-bold text-muted mb-0"><?php echo $b['name']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
</div>

<script>
    var swiper = new Swiper(".brandSwiper", {
        slidesPerView: 2,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: {
                slidesPerView: 3
            },
            768: {
                slidesPerView: 4
            },
            1024: {
                slidesPerView: 6
            }, // Hiển thị 6 ảnh trên màn hình máy tính
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>