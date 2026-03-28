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
                    <p><strong>Mã đơn:</strong> <span id="order_code"></span></p>
                    <p><strong>Sản phẩm:</strong> <span id="product_name"></span></p>
                    <p><strong>Số lượng:</strong> <span id="quantity"></span></p>
                    <p><strong>Giá:</strong> <span id="price"></span></p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    window.fillEditForm = function(data) {
        const item = data[0]; // vì data là array

        document.getElementById("order_code").textContent = item.order_code;
        document.getElementById("product_name").textContent = item.product_name;
        document.getElementById("quantity").textContent = item.quantity;
        document.getElementById("price").textContent =
            Number(item.price).toLocaleString() + " đ";

        const modal = new bootstrap.Modal(document.getElementById("modalEdit"));
        modal.show();
    };
</script>