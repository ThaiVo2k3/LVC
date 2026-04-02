<div class="lg:ml-64 min-h-screen bg-gray-100 p-4">
    <div class="bg-white rounded-xl shadow p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-lg font-semibold">Danh sách sản phẩm</h2>

            <button
                class="btn btn-primary d-flex align-items-center gap-2"
                data-bs-toggle="modal"
                data-bs-target="#modalAdd">
                <i class="fa-solid fa-plus"></i>
                Thêm sản phẩm
            </button>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Ảnh</th>
                    <th>Danh mục</th>
                    <th>Hãng</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= e($product['id']) ?></td>
                        <td><?= e($product['name']) ?></td>
                        <td><?= e($product['price']) ?></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img
                                    src="/public/uploads/products/<?= e($product['id']) ?>/<?= e($product['image']) ?>"
                                    alt="<?= e($product['name']) ?>"
                                    class="w-[120px] h-[60px] object-contain bg-gray-100 rounded border p-1">
                            <?php else: ?>
                                <span class="text-muted">Không có</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($product['category_name']) ?></td>
                        <td><?= e($product['brand_name']) ?></td>

                        <td class="text-end">
                            <button
                                class="btn btn-sm btn-warning me-2"
                                data-action="edit"
                                data-name="<?= e($product['name']) ?>"
                                data-url="/admin/products/get/<?= $product['id'] ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                                data-callback="fillEditForm">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button
                                class="btn btn-sm btn-danger"
                                data-action="action"
                                data-name="xóa <?= e($product['name']) ?>"
                                data-url="/admin/products/delete/<?= $product['id'] ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="modalAdd">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">

                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form class="form-post" action="/admin/products/add" enctype="multipart/form-data">
                    <div class="modal-body space-y-3">

                        <input type="text" name="name" class="form-control" placeholder="Tên sản phẩm" required>
                        <input type="number" name="price" class="form-control" placeholder="Giá" required>
                        <select name="brand_id" class="form-control" required>
                            <option value="">--hãng sản xuất--</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id'] ?>"><?= e($brand['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="category_id" class="form-control" required>
                            <option value="">--danh mục--</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= e($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <textarea type="text" name="description" class="form-control" placeholder="mô tả" require></textarea>
                        <input type="text" name="resolution" class="form-control" placeholder="độ phân giải" required>
                        <input type="text" name="fps" class="form-control" placeholder="fps" required>
                        <input type="text" name="lens" class="form-control" placeholder="ống kính" required>
                        <input type="file" name="image" class="form-control">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button class="btn btn-primary">Thêm</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-sm">

                <!-- Header -->
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Form -->
                <form class="form-post" enctype="multipart/form-data">

                    <div class="modal-body d-flex flex-column gap-3">

                        <!-- Name -->
                        <div class="form-floating">
                            <input type="text" id="edit_name" name="name" class="form-control" placeholder="Tên sản phẩm" required>
                            <label for="edit_name">Tên sản phẩm</label>
                        </div>

                        <!-- Old Image -->
                        <div>
                            <label class="form-label fw-medium">Ảnh hiện tại</label>
                            <img id="edit_old_img" class="w-100 rounded border" style="height:120px;object-fit:cover">
                        </div>

                        <!-- New Image -->
                        <div>
                            <label class="form-label">Ảnh mới</label>
                            <input type="file" name="anh_new" class="form-control">
                        </div>

                        <!-- Price -->
                        <div class="form-floating">
                            <input type="number" id="edit_price" name="price" class="form-control" placeholder="Giá" required>
                            <label for="edit_price">Giá</label>
                        </div>

                        <!-- Brand -->
                        <div class="form-floating">
                            <select id="edit_brand_id" name="brand_id" class="form-select" required>
                                <option value="">-- Chọn hãng --</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id'] ?>"><?= e($brand['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="edit_brand_id">Hãng sản xuất</label>
                        </div>

                        <!-- category_id -->
                        <div class="form-floating">
                            <select id="edit_category_id" name="category_id" class="form-select" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= e($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="edit_category_id">sản phẩm</label>
                        </div>

                        <!-- Description -->
                        <div class="form-floating">
                            <textarea id="edit_description" name="description" class="form-control" placeholder="Mô tả" style="height:100px" required></textarea>
                            <label for="edit_description">Mô tả</label>
                        </div>

                        <!-- Specs Grid -->
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" id="edit_resolution" name="resolution" class="form-control" placeholder="Độ phân giải" required>
                                    <label for="edit_resolution">Độ phân giải</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" id="edit_fps" name="fps" class="form-control" placeholder="FPS" required>
                                    <label for="edit_fps">FPS</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" id="edit_lens" name="lens" class="form-control" placeholder="Ống kính" required>
                                    <label for="edit_lens">Ống kính</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-warning fw-semibold">Cập nhật</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


</div>

<script>
    window.fillEditForm = function(data) {
        document.getElementById("edit_name").value = data.name || "";
        document.getElementById("edit_price").value = data.price || "";
        document.getElementById("edit_brand_id").value = data.brand_id || "";
        document.getElementById("edit_category_id").value = data.category_id || "";
        document.getElementById("edit_description").value = data.description || "";
        document.getElementById("edit_resolution").value = data.camera_resolution || "";
        document.getElementById("edit_fps").value = data.camera_fps || "";
        document.getElementById("edit_lens").value = data.camera_lens || "";

        const imgEl = document.getElementById("edit_old_img");
        if (data.image) {
            imgEl.src = `/public/uploads/products/${data.id}/${data.image}`;
        } else {
            imgEl.src = "";
        }

        document.querySelectorAll(".form-post").forEach((form) => {
            form.action = `/admin/products/update/${data.id}`;
        });
    };
</script>