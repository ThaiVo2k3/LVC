<div class="modal fade" id="loginModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header">
                <h5 class="modal-title">Đăng nhập</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form class="form-post" action="/login" method="POST">
                <div class="modal-body space-y-3">

                    <input type="text" name="sodienthoai"
                        class="form-control"
                        placeholder="Số điện thoại" required>

                    <input type="password" name="matkhau"
                        class="form-control"
                        placeholder="Mật khẩu" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button class="btn btn-primary">Đăng nhập</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="registerModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header">
                <h5 class="modal-title">Đăng ký</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form class="form-post" action="/register" method="POST">
                <div class="modal-body space-y-3">

                    <input type="text" name="ho_ten" class="form-control" placeholder="Họ tên" required>
                    <input type="text" name="so_dien_thoai" class="form-control" placeholder="SĐT" required>
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <input type="password" name="mat_khau" class="form-control" placeholder="Mật khẩu" required>
                    <input type="password" name="xac_nhan" class="form-control" placeholder="Nhập lại mật khẩu" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button class="btn btn-success">Đăng ký</button>
                </div>
            </form>

        </div>
    </div>
</div>

<footer class="bg-dark text-white mt-5">
    <div class="container py-5">
        <div class="row">

            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">Về chúng tôi</h5>
                <ul class="list-unstyled">
                    <li>Giới thiệu</li>
                    <li>Tuyển dụng</li>
                    <li>Góp ý</li>
                </ul>
            </div>

            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">Hỗ trợ</h5>
                <ul class="list-unstyled">
                    <li>Hotline: 1900 1234</li>
                    <li>Email: cskh@example.com</li>
                    <li>Hướng dẫn</li>
                </ul>
            </div>

            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">Chính sách</h5>
                <ul class="list-unstyled">
                    <li>Bảo hành</li>
                    <li>Đổi trả</li>
                    <li>Thanh toán</li>
                </ul>
            </div>

            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">Kết nối</h5>
                <div class="flex gap-3 text-xl">
                    <i class="fab fa-facebook hover:text-blue-400"></i>
                    <i class="fab fa-youtube hover:text-red-500"></i>
                    <i class="fab fa-tiktok"></i>
                </div>
            </div>

        </div>

        <div class="text-center mt-4 border-top pt-3 text-sm text-gray-400">
            © 2025 Camera Store - Design by VQT
        </div>
    </div>
</footer>