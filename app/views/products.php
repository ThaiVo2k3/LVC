<form id="filterForm" method="get"
    class="bg-white p-6 rounded-2xl shadow mb-6 border">

    <div class="flex flex-wrap gap-3 items-center">

        <select name="category"
            class="border rounded-full px-4 py-2 focus:ring-2 focus:ring-blue-400">
            <option value="">Danh mục</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $category_id == $c['id'] ? 'selected' : '' ?>>
                    <?= $c['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="brand"
            class="border rounded-full px-4 py-2 focus:ring-2 focus:ring-blue-400">
            <option value="">Hãng</option>
            <?php foreach ($brands as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $brand_id == $b['id'] ? 'selected' : '' ?>>
                    <?= $b['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="number" name="min_price" placeholder="Từ"
            value="<?= $_GET['min_price'] ?? '' ?>"
            class="border rounded-full px-4 py-2 w-28">

        <input type="number" name="max_price" placeholder="Đến"
            value="<?= $_GET['max_price'] ?? '' ?>"
            class="border rounded-full px-4 py-2 w-28">

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-full">
            Lọc
        </button>

        <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>"
            class="bg-gray-200 hover:bg-gray-300 px-5 py-2 rounded-full">
            Reset
        </a>

    </div>
</form>
<div class="flex justify-between items-center mb-4">

    <div class="ml-auto">
        <select onchange="applySorting(this.value)"
            class="border rounded-xl px-3 py-2 shadow-sm">

            <option value="newest" <?= ($_GET['sort'] ?? 'newest') == 'newest' ? 'selected' : '' ?>>
                Mới nhất
            </option>

            <option value="price_asc" <?= ($_GET['sort'] ?? '') == 'price_asc' ? 'selected' : '' ?>>
                Giá thấp → cao
            </option>

            <option value="price_desc" <?= ($_GET['sort'] ?? '') == 'price_desc' ? 'selected' : '' ?>>
                Giá cao → thấp
            </option>
        </select>
    </div>

</div>
<!-- PRODUCT GRID -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

    <?php foreach ($products as $p): ?>
        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

            <!-- Image -->
            <div class="h-48 bg-gray-100 flex items-center justify-center">
                <img src="/public/uploads/products/<?= $p['id'] ?>/<?= $p['image'] ?>"
                    class="h-full object-contain hover:scale-105 transition duration-300">
            </div>

            <!-- Content -->
            <div class="p-4">

                <h4 class="font-semibold text-lg line-clamp-2 mb-2">
                    <?= $p['name'] ?>
                </h4>

                <p class="text-sm text-gray-500 mb-1">
                    <?= $p['brand_name'] ?>
                </p>

                <p class="text-red-500 font-bold text-lg mb-3">
                    <?= number_format($p['price']) ?>đ
                </p>

                <a href="<?= urlSanPham($p['name'], $p['id']) ?>"
                    class="block text-center bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
                    Xem chi tiết
                </a>

            </div>
        </div>
    <?php endforeach; ?>

</div>
<?php
$query = $_GET;

unset($query['url']);
unset($query['page']);

// loại bỏ rỗng
$query = array_filter($query);
?>

<div class="flex justify-center mt-6 gap-2">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?<?= http_build_query(array_merge($query, ['page' => $i])) ?>"
            class="px-3 py-1 border rounded 
            <?= $i == ($_GET['page'] ?? 1) ? 'bg-blue-500 text-white' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
<!-- EMPTY -->
<?php if (empty($products)): ?>
    <div class="text-center mt-16">
        <p class="text-gray-400 text-lg">Không có sản phẩm nào</p>
    </div>
<?php endif; ?>

<script>
    function redirectWithParams(params) {
        params.delete('url'); // 🔥 loại url
        params.delete('page'); // reset page

        const query = params.toString();

        window.location.href = query ?
            '?' + query :
            window.location.pathname;

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // FILTER
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                params.set(key, value);
            }
        }

        // giữ sort nếu có
        const currentParams = new URLSearchParams(window.location.search);
        if (currentParams.get('sort')) {
            params.set('sort', currentParams.get('sort'));
        }

        redirectWithParams(params);
    });

    // SORT
    function applySorting(sortValue) {
        const params = new URLSearchParams(window.location.search);

        if (sortValue === 'newest') {
            params.delete('sort');
        } else {
            params.set('sort', sortValue);
        }

        redirectWithParams(params);
    }
</script>