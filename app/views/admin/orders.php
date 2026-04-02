<div class="lg:ml-64 min-h-screen bg-gray-100 p-4">
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>mã</th>
                    <th>người mua</th>
                    <th>tông tiền</th>
                    <th>địa chỉ</th>
                    <th>trang thái</th>
                    <th>thanh toán</th>
                    <th>ngày tạo</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= e($order['code']) ?></td>
                        <td><?= e($order['user_name']) ?></td>
                        <td><?= e($order['total_price']) ?></td>
                        <td><?= e($order['address']) ?></td>
                        <td><?= e($order['status']) ?></td>
                        <td><?= e($order['payment_status']) ?></td>
                        <td><?= e($order['created_at']) ?></td>
                        <td class="text-end">
                            <button
                                class="btn btn-sm btn-info"
                                data-action="edit"
                                data-url="/admin/orders/get/<?= $order['id'] ?>"
                                data-popup="modalEdit"
                                data-callback="fillEditForm">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">

                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết đơn hàng</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-3">
                        <strong>Mã đơn:</strong>
                        <span id="order_code" class="text-primary fw-bold"></span>
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end">Giá</th>
                                </tr>
                            </thead>
                            <tbody id="order_items"></tbody>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <strong>Tổng tiền:</strong>
                        <span id="total_price" class="text-danger fw-bold"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    window.fillEditForm = function(items) {
        if (!items || items.length === 0) {
            alert("Không có dữ liệu");
            return;
        }
        document.getElementById("order_code").textContent = items[0].order_code;
        const container = document.getElementById("order_items");
        container.innerHTML = items.map(item => `
        <tr>
            <td>${item.product_name}</td>
            <td class="text-center">${item.quantity}</td>
            <td class="text-end text-danger fw-semibold">
                ${Number(item.price).toLocaleString()} đ
            </td>
        </tr>
    `).join("");
        const total = items.reduce((sum, i) => {
            return sum + Number(i.price) * i.quantity;
        }, 0);
        document.getElementById("total_price").textContent =
            total.toLocaleString() + " đ";
        const modal = new bootstrap.Modal(document.getElementById("modalEdit"));
        modal.show();
    };
</script>