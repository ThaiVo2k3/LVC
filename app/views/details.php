<div class="max-w-6xl mx-auto px-4 py-6">

    <!-- GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-2xl shadow">

        <!-- IMAGE -->
        <div class="flex flex-col items-center">

            <div class="w-full bg-gray-100 rounded-xl overflow-hidden">
                <img src="/public/uploads/products/<?= $product['id'] ?>/<?= $product['image'] ?>"
                    class="w-full h-[300px] md:h-[400px] object-contain hover:scale-105 transition">
            </div>

            <!-- Thumbnail (nâng cấp sau nếu có nhiều ảnh) -->
            <div class="flex gap-2 mt-3">
                <img src="/public/uploads/products/<?= $product['id'] ?>/<?= $product['image'] ?>"
                    class="w-16 h-16 border rounded-lg object-cover cursor-pointer">
            </div>

        </div>

        <!-- INFO -->
        <div class="flex flex-col">

            <h1 class="text-2xl md:text-3xl font-bold mb-2">
                <?= $product['name'] ?>
            </h1>

            <!-- Brand -->
            <p class="text-gray-500 mb-3">
                Thương hiệu: <span class="font-medium"><?= $product['brand_name'] ?? 'N/A' ?></span>
            </p>

            <!-- Price -->
            <div class="text-red-500 text-3xl font-bold mb-4">
                <?= number_format($product['price']) ?>đ
            </div>

            <!-- Short description -->
            <p class="text-gray-600 mb-6">
                <?= $product['description'] ?>
            </p>

            <!-- ACTION -->
            <div class="flex flex-col sm:flex-row gap-3 mb-6">

                <form action="/add-to-cart" method="POST" class="form-post flex-1">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="quantity" value="1">

                    <button
                        type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-semibold">
                        🛒 Thêm vào giỏ
                    </button>
                </form>

                <form action="/cart/checkout" method="POST" class="form-post flex-1">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="quantity" value="1">

                    <button
                        type="submit"
                        class="w-full border border-black hover:bg-black hover:text-white px-6 py-3 rounded-xl font-semibold">
                        Mua ngay
                    </button>
                </form>

            </div>
            <!-- TECH SPECS -->
            <div class="border-t pt-4">

                <h3 class="text-lg font-semibold mb-3">Thông số kỹ thuật</h3>

                <div class="grid grid-cols-2 gap-3 text-sm">

                    <div class="text-gray-500">Độ phân giải</div>
                    <div class="font-medium"><?= $product['camera_resolution'] ?></div>

                    <div class="text-gray-500">FPS</div>
                    <div class="font-medium"><?= $product['camera_fps'] ?></div>

                    <div class="text-gray-500">Ống kính</div>
                    <div class="font-medium"><?= $product['camera_lens'] ?></div>

                </div>
            </div>

        </div>
    </div>

    <!-- RELATED (OPTIONAL) -->
    <div class="mt-12">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl md:text-2xl font-bold">Sản phẩm liên quan</h2>
        </div>

        <?php if (!empty($sameCategoryProducts)): ?>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">

                <?php foreach ($sameCategoryProducts as $p): ?>
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden group">

                        <!-- IMAGE -->
                        <div class="h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
                            <img src="/public/uploads/products/<?= $p['id'] ?>/<?= $p['image'] ?>"
                                class="h-full object-contain group-hover:scale-105 transition duration-300">
                        </div>

                        <!-- CONTENT -->
                        <div class="p-3">

                            <!-- NAME -->
                            <h3 class="text-sm font-semibold line-clamp-2 min-h-[40px]">
                                <?= $p['name'] ?>
                            </h3>

                            <!-- BRAND -->
                            <p class="text-xs text-gray-400 mt-1">
                                <?= $p['brand_name'] ?? '' ?>
                            </p>

                            <!-- PRICE -->
                            <p class="text-red-500 font-bold mt-2">
                                <?= number_format($p['price']) ?>đ
                            </p>

                            <!-- BUTTON -->
                            <a href="<?= urlSanPham($p['name'], $p['id']) ?>"
                                class="block text-center mt-3 bg-black text-white py-2 rounded-lg text-sm hover:bg-gray-800 transition">
                                Xem chi tiết
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php else: ?>

            <!-- EMPTY -->
            <div class="text-center text-gray-400 mt-6">
                Không có sản phẩm liên quan
            </div>

        <?php endif; ?>
    </div>

</div>