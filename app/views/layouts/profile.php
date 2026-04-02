<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <!-- Open Graph cho Facebook & Zalo -->
    <meta property="og:title" content="ThaiVo2k3 - camera quan sát Chính Hãng Giá Tốt">
    <meta property="og:description" content="Chuyên cung cấp camera quan sát chính hãng, giá tốt nhất thị trường. Bảo hành 12 tháng, giao hàng toàn quốc, hỗ trợ trả góp 0%.">
    <meta property="og:image" content="https://thaivo2k3.id.vn/public/uploads/ogimage.jpg">
    <meta property="og:url" content="https://thaivo2k3.id.vn">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ThaiVo2k3 camera quan sát">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="ThaiVo2k3 - Cửa hàng camera quan sát uy tín">
    <link rel="stylesheet" href="/public/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/public/js/main.js"></script>

</head>

<body
    data-error="<?= e($_SESSION['error'] ?? '') ?>"
    data-success="<?= e($_SESSION['success'] ?? '') ?>">
    <?php unset($_SESSION['success'], $_SESSION['error']); ?>
    <?php require "./app/views/parts/header.php"; ?>
    <main class="container-fluid mt-4 px-3">
        <div class="row g-3">

            <div class="col-md-3">
                <?php require "./app/views/parts/profile_sidebar.php"; ?>
            </div>

            <div class="col-md-9">
                <div class="bg-white p-4 rounded shadow-sm">
                    <?php require $content; ?>
                </div>
            </div>

        </div>
    </main>

    <?php require "./app/views/parts/footer.php"; ?>
</body>

</html>