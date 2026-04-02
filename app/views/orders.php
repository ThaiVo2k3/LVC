<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã</th>
                    <th>Người mua</th>
                    <th>Tổng tiền</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ngày</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                    $statusColor = match ($order['status']) {
                        'hoàn thành' => 'success',
                        'đang xử lý' => 'primary',
                        'đã hủy' => 'danger',
                        default => 'warning'
                    };
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= e($order['code']) ?></td>

                        <td><?= e($order['user_name']) ?></td>

                        <td class="text-danger fw-bold">
                            <?= number_format($order['total_price']) ?>đ
                        </td>

                        <td class="text-muted"><?= e($order['address']) ?></td>

                        <td>
                            <span class="badge bg-<?= $statusColor ?>">
                                <?= e($order['status']) ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-info">
                                <?= e($order['payment_status']) ?>
                            </span>
                        </td>

                        <td class="text-muted small">
                            <?= e($order['created_at']) ?>
                        </td>

                        <td class="text-end space-x-1">

                            <button
                                class="btn btn-sm btn-light border"
                                data-action="edit"
                                data-url="/orders/detail/<?= $order['id'] ?>"
                                data-popup="modalEdit"
                                data-callback="fillOrderDetail">
                                <i class="fa-solid fa-eye text-primary"></i>
                            </button>

                            <button
                                class="btn btn-sm btn-light border"
                                data-action="action"
                                data-url="/orders/cancel/<?= $order['id'] ?>"
                                data-name="hủy đơn: <?= e($order['code']) ?>">
                                <i class="fa-solid fa-ban text-warning"></i>
                            </button>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm border-0">

            <div class="modal-header border-b">
                <h5 class="modal-title fw-bold">Chi tiết đơn hàng</h5>
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
                                <th class="text-end">Tổng</th>
                            </tr>
                        </thead>
                        <tbody id="order_items"></tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <strong>Tổng tiền:</strong>
                    <span id="total_price" class="text-danger fw-bold fs-5"></span>
                </div>

            </div>

            <div class="modal-footer border-0">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Đóng
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    window.fillOrderDetail = function(items) {
        if (!items || items.length === 0) {
            showAlert({
                error: "Không có dữ liệu"
            });
            return;
        }

        document.getElementById("order_code").textContent =
            items[0].order_code;

        const container = document.getElementById("order_items");

        container.innerHTML = items.map(item => `
        <tr>
            <td class="fw-medium">${item.product_name}</td>
            <td class="text-center">${item.quantity}</td>
            <td class="text-end">
                ${Number(item.price).toLocaleString()} đ
            </td>
            <td class="text-end text-danger fw-semibold">
                ${(item.price * item.quantity).toLocaleString()} đ
            </td>
        </tr>
    `).join("");

        const total = items.reduce((sum, i) =>
            sum + Number(i.price) * i.quantity, 0
        );

        document.getElementById("total_price").textContent =
            total.toLocaleString() + " đ";

        // mở modal giống admin
        const modal = new bootstrap.Modal(
            document.getElementById("modalEdit")
        );
        modal.show();
    };
</script>