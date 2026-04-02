<div class="container py-5">
    <h2 class="fw-bold text-center mb-4">🛒 Giỏ hàng của bạn</h2>
    <div id="cart-empty" class="<?= empty($cart) ? '' : 'd-none' ?>">
        <div class="alert alert-warning text-center">
            Giỏ hàng trống 😢
        </div>
    </div>
    <div id="cart-wrapper" class="<?= empty($cart) ? 'd-none' : '' ?>">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="bg-white shadow rounded-4 p-3">
                    <div class="mb-3">
                        <input type="checkbox" id="check-all">
                        <label for="check-all">Chọn tất cả</label>
                    </div>
                    <div id="cart-body">
                        <?php foreach ($cart as $item): ?>
                            <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-3"
                                data-id="<?= $item['id'] ?>">
                                <input type="checkbox" class="item-check">
                                <div class="d-flex align-items-center gap-3 flex-grow-1 px-3">
                                    <img src="/public/uploads/products/<?= $item['id'] ?>/<?= $item['image'] ?>"
                                        width="70" class="rounded">
                                    <div>
                                        <div class="fw-semibold"><?= $item['name'] ?></div>
                                        <div class="text-danger fw-bold">
                                            <?= number_format($item['price']) ?> đ
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-outline-secondary btn-sm"
                                    onclick="updateQty(<?= $item['id'] ?>,'decrease')">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <span class="mx-2 qty fw-bold"><?= $item['quantity'] ?></span>
                                <button class="btn btn-outline-secondary btn-sm"
                                    onclick="updateQty(<?= $item['id'] ?>,'increase')">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-danger ms-3"
                                    onclick="removeItem(<?= $item['id'] ?>)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-white shadow rounded-4 p-4 position-sticky" style="top: 100px">
                    <h5 class="fw-bold mb-3">Thanh toán</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Số sản phẩm chọn:</span>
                        <span id="selected-count">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tổng tiền:</span>
                        <span id="selected-total" class="text-danger fw-bold">0 đ</span>
                    </div>
                    <button class="btn btn-success w-100 form-post" onclick="openCheckoutModal()">
                        Thanh toán
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">

            <form class="form-post" action="/cart/checkout" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Xác nhận đặt hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Người đặt -->
                    <div class="mb-3">
                        <label class="fw-semibold">Người đặt:</label>
                        <div><?= $_SESSION['user']['name'] ?? 'Chưa đăng nhập' ?></div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div id="checkout-items"></div>

                    <!-- Tổng tiền -->
                    <div class="mt-3">
                        <div class="flex justify-between font-bold">
                            <span>Tổng cộng:</span>
                            <span id="checkout-total" class="text-danger"></span>
                        </div>
                    </div>

                    <!-- Địa chỉ -->
                    <div class="mt-3">
                        <label>Địa chỉ nhận:</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <!-- Thanh toán -->
                    <div class="mt-3">
                        <label>Phương thức thanh toán:</label>
                        <select name="payment" class="form-select">
                            <option value="cod">COD</option>
                            <option value="online">Online</option>
                        </select>
                    </div>

                    <!-- Hidden chứa sản phẩm -->
                    <div id="checkout-hidden"></div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Xác nhận thanh toán
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
    function openCheckoutModal() {
        let selected = [];
        let html = '';
        let total = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            let cb = item.querySelector('.item-check');
            if (!cb.checked) return;

            let id = item.dataset.id;
            let name = item.querySelector('.fw-semibold').innerText;
            let price = parseInt(item.querySelector('.text-danger').innerText.replace(/\D/g, ''));
            let qty = parseInt(item.querySelector('.qty').innerText);

            let subTotal = price * qty;
            total += subTotal;

            selected.push({
                id,
                qty
            });

            html += `
            <div class="d-flex justify-content-between border-bottom py-2">
                <div>
                    <div>${name}</div>
                    <small>${formatMoney(price)} x ${qty}</small>
                </div>
                <div class="text-danger fw-bold">
                    ${formatMoney(subTotal)}
                </div>
            </div>
        `;
        });

        if (selected.length === 0) {
            showAlert({
                error: "Vui lòng chọn sản phẩm"
            });
            return;
        }

        // 👉 render danh sách sản phẩm
        document.getElementById('checkout-items').innerHTML = html;

        // 👉 tổng tiền
        document.getElementById('checkout-total').innerText = formatMoney(total);

        // 👉 hidden input
        const container = document.getElementById('checkout-hidden');
        container.innerHTML = '';

        selected.forEach(item => {
            container.innerHTML += `
            <input type="hidden" name="products[${item.id}]" value="${item.qty}">
        `;
        });

        const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        modal.show();
    }

    function saveSelected() {
        localStorage.setItem('cart_selected', JSON.stringify(getSelectedIds()));
    }

    function loadSelectedIds() {
        return JSON.parse(localStorage.getItem('cart_selected') || '[]');
    }

    function formatMoney(num) {
        return new Intl.NumberFormat('vi-VN').format(num) + ' đ';
    }

    function getSelectedIds() {
        let selected = [];

        document.querySelectorAll('.cart-item').forEach(item => {
            let cb = item.querySelector('.item-check');
            if (cb && cb.checked) {
                selected.push(String(item.dataset.id));
            }
        });

        return selected;
    }

    function updateSelected() {
        let total = 0;
        let count = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            let checked = item.querySelector('.item-check').checked;
            if (!checked) return;

            let price = parseInt(item.querySelector('.text-danger').innerText.replace(/\D/g, ''));
            let qty = parseInt(item.querySelector('.qty').innerText);

            total += price * qty;
            count++;
        });

        document.getElementById('selected-total').innerText = formatMoney(total);
        document.getElementById('selected-count').innerText = count;
    }
    document.addEventListener('change', function(e) {
        if (e.target.id === 'check-all') {
            let checked = e.target.checked;

            document.querySelectorAll('.item-check').forEach(cb => {
                cb.checked = checked;
            });
        }
        if (e.target.classList.contains('item-check')) {
            let total = document.querySelectorAll('.item-check').length;
            let checked = document.querySelectorAll('.item-check:checked').length;

            document.getElementById('check-all').checked = (total === checked);
        }

        updateSelected();
        saveSelected();
    });

    function loadSelected() {
        let saved = loadSelectedIds();

        document.querySelectorAll('.cart-item').forEach(item => {
            if (saved.includes(String(item.dataset.id))) {
                item.querySelector('.item-check').checked = true;
            }
        });

        updateSelected();
        let total = document.querySelectorAll('.item-check').length;
        let checked = document.querySelectorAll('.item-check:checked').length;

        document.getElementById('check-all').checked = (total > 0 && total === checked);
    }

    document.addEventListener('DOMContentLoaded', loadSelected);

    function updateQty(id, action) {
        fetch('/cart/update-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${id}&action=${action}`
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) return alert(data.message);
                renderCart(data.cart);
            });
    }

    function removeItem(id) {
        fetch('/cart/remove-item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${id}`
            })
            .then(res => res.json())
            .then(data => {
                renderCart(data.cart);

                document.getElementById('cart-count').innerText = data.count;
            });
    }

    function renderCart(cart) {
        let selectedIds = getSelectedIds();
        let oldOrder = [];
        document.querySelectorAll('.cart-item').forEach(item => {
            oldOrder.push(String(item.dataset.id));
        });
        let items = Object.values(cart);
        items.sort((a, b) => {
            return oldOrder.indexOf(String(a.id)) - oldOrder.indexOf(String(b.id));
        });
        let html = '';
        items.forEach(item => {
            let checked = selectedIds.includes(String(item.id)) ? 'checked' : '';

            html += `
        <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-3"
            data-id="${item.id}">

            <input type="checkbox" class="item-check" ${checked}>

            <div class="d-flex align-items-center gap-3 flex-grow-1 px-3">
                <img src="/public/uploads/products/${item.id}/${item.image}"
                    width="70" class="rounded">

                <div>
                    <div class="fw-semibold">${item.name}</div>
                    <div class="text-danger fw-bold">
                        ${formatMoney(item.price)}
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm"
                    onclick="updateQty(${item.id}, 'decrease')">
                    <i class="fa-solid fa-minus"></i>
                </button>

                <span class="mx-2 qty fw-bold">${item.quantity}</span>

                <button class="btn btn-outline-secondary btn-sm"
                    onclick="updateQty(${item.id}, 'increase')">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>

            <button class="btn btn-sm btn-danger ms-3"
                onclick="removeItem(${item.id})">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
        `;
        });
        document.getElementById('cart-body').innerHTML = html;
        updateSelected();
        saveSelected();
        if (items.length === 0) {
            document.getElementById('cart-wrapper').classList.add('d-none');
            document.getElementById('cart-empty').classList.remove('d-none');
        } else {
            document.getElementById('cart-wrapper').classList.remove('d-none');
            document.getElementById('cart-empty').classList.add('d-none');
        }
        let total = document.querySelectorAll('.item-check').length;
        let checked = document.querySelectorAll('.item-check:checked').length;

        document.getElementById('check-all').checked = (total > 0 && total === checked);
    }
</script>