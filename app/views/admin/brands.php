<div class="lg:ml-64 min-h-screen bg-gray-100 p-4">
    <div class="bg-white rounded-xl shadow p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-lg font-semibold">Danh sách danh mục</h2>

            <button
                class="btn btn-primary d-flex align-items-center gap-2"
                data-bs-toggle="modal"
                data-bs-target="#modalAdd">
                <i class="fa-solid fa-plus"></i>
                Thêm danh mục
            </button>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Đường dẫn</th>
                    <th>Tên</th>
                    <th>Ảnh</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($brands as $brand): ?>
                    <tr>
                        <td><?= e($brand['id']) ?></td>
                        <td><?= e($brand['name']) ?></td>

                        <td>
                            <?php if (!empty($brand['image'])): ?>
                                <img
                                    src="/public/uploads/brands/<?= e($brand['id']) ?>/<?= e($brand['image']) ?>"
                                    alt="<?= e($brand['name']) ?>"
                                    class="rounded border"
                                    style="width:60px;height:60px;object-fit:cover;">
                            <?php else: ?>
                                <span class="text-muted">Không có</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-end">
                            <button
                                class="btn btn-sm btn-warning me-2"
                                data-action="edit"
                                data-name="<?= e($brand['name']) ?>"
                                data-url="/admin/brands/get/<?= $brand['id'] ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                                data-callback="fillEditForm">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button
                                class="btn btn-sm btn-danger"
                                data-action="delete"
                                data-name="<?= e($brand['name']) ?>"
                                data-url="/admin/brands/delete/<?= $brand['id'] ?>">
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
                    <h5 class="modal-title">Thêm danh mục</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form class="form-post" action="/admin/brands/add" enctype="multipart/form-data">
                    <div class="modal-body space-y-3">

                        <input type="text" name="name" class="form-control" placeholder="Tên danh mục" required>
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
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">

                <div class="modal-header">
                    <h5 class="modal-title">Sửa danh mục</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form class="form-post" enctype="multipart/form-data">
                    <div class="modal-body space-y-3">

                        <input type="text" id="edit_name" name="name" class="form-control" placeholder="Tên danh mục" required>

                        <div>
                            <label>Ảnh hiện tại</label>
                            <img id="edit_old_img" class="w-100 rounded border" style="height:120px;object-fit:cover">
                        </div>

                        <input type="file" name="anh_new" class="form-control">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button class="btn btn-warning">Cập nhật</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


</div>
<script>
    window.fillEditForm = function(data) {
        document.getElementById("edit_name").value = data.name || "";

        const imgEl = document.getElementById("edit_old_img");
        if (data.image) {
            imgEl.src = `/public/uploads/brands/${data.id}/${data.image}`;
        } else {
            imgEl.src = "";
        }

        document.querySelectorAll(".form-post").forEach((form) => {
            form.action = `/admin/brands/update/${data.id}`;
        });
    };
</script>