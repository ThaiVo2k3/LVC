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
                    <th>Tên</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= e($category['id']) ?></td>
                        <td><?= e($category['name']) ?></td>

                        <td class="text-end">
                            <button
                                class="btn btn-sm btn-warning me-2"
                                data-action="edit"
                                data-name="<?= e($category['name']) ?>"
                                data-url="/admin/categories/get/<?= $category['id'] ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                                data-callback="fillEditForm">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button
                                class="btn btn-sm btn-danger"
                                data-action="delete"
                                data-name="<?= e($category['name']) ?>"
                                data-url="/admin/categories/delete/<?= $category['id'] ?>">
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

                <form class="form-post" action="/admin/categories/add" enctype="multipart/form-data">
                    <div class="modal-body space-y-3">
                        <input type="text" name="name" class="form-control" placeholder="Tên danh mục" required>
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
        document.querySelectorAll(".form-post").forEach((form) => {
            form.action = `/admin/categories/update/${data.id}`;
        });
    };
</script>