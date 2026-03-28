<?php

class WebhooksController extends BaseController
{
    protected $paymentModel;
    protected $orderModel;
    private $userModel;
    private $telegramService;
    private $productModel;

    public function __construct()
    {

        $this->paymentModel = new Payment();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->telegramService = new TelegramService();
        $this->productModel = new Product();
    }

    public function handle()
    {
        header('Content-Type: application/json');

        $headers = function_exists('getallheaders') ? getallheaders() : [];
        if (($headers['Authorization'] ?? '') !== 'Apikey voquocthai2k3abcxyz') {
            http_response_code(401);
            echo json_encode([
                'status' => 'unauthorized',
                'message' => 'Invalid API key'
            ]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));
        if (!is_object($data)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'No data'
            ]);
            return;
        }

        $maGiaoDich = $data->referenceCode ?? '';
        $noiDung    = trim($data->content ?? '');
        $soTien     = (int)($data->transferAmount ?? 0);

        preg_match('/\bDH[A-Z0-9]{8}\b/', $noiDung, $matches);


        if (empty($matches)) {
            echo json_encode([
                'status' => 'ignored',
                'message' => 'Không tìm thấy mã đơn hàng trong nội dung'
            ]);
            return;
        }

        $maDonHang = $matches[0];

        $donHang = $this->orderModel->getOrderByCode($maDonHang);

        if (!$donHang) {
            echo json_encode([
                'status' => 'ignored',
                'message' => 'Không tìm thấy đơn hàng'
            ]);
            return;
        }

        if ($donHang['trang_thai_thanh_toan'] === 'Đã thanh toán') {
            echo json_encode([
                'status' => 'duplicate',
                'message' => 'Đơn hàng đã được thanh toán'
            ]);
            return;
        }

        if ($this->paymentModel->isDuplicateTransaction($maGiaoDich)) {
            echo json_encode([
                'status' => 'duplicate',
                'message' => 'Giao dịch đã xử lý'
            ]);
            return;
        }
        $nguoiDungId = $donHang['nguoi_dung_id'];

        $ok = $this->paymentModel->addPayment([
            'nguoi_dung_id' => $nguoiDungId,
            'so_tien'       => $soTien,
            'ma_giao_dich'  => $maGiaoDich,
            'noi_dung'      => $noiDung,
            'nguoi_gui'     => $data->accountNumber ?? '',
            'thoi_gian'     => date('Y-m-d H:i:s')
        ]);
        if ($ok) {
            $nguoiDung = $this->userModel->getUserById($nguoiDungId);
            $hoTen = $nguoiDung['ho_ten'];
            $message =
                "<b>🛒 NHẬN TIỀN</b>\n" .
                "Mã đơn hàng: <b>$maDonHang</b>\n" .
                "Họ tên: <b>$hoTen</b>\n" .
                "Số tiền: <b>+ " . number_format($soTien) . "đ</b>";

            $this->telegramService->send($message);
        }
        $tongTienDonHang = (int)$donHang['tong_tien'];

        if ($soTien !== $tongTienDonHang) {
            echo json_encode([
                'status' => 'mismatch',
                'message' => 'Số tiền không khớp',
                'so_tien_bank' => $soTien,
                'tong_tien_don_hang' => $tongTienDonHang
            ]);
            return;
        }
        $ok = $this->orderModel->markAsPaid($donHang['don_hang_id']);
        if ($ok) {
            $sanPham = $this->productModel->getProductById($donHang['san_pham_id']);
            $tenSanPham = $sanPham['ten_san_pham'];
            $soLuong = $donHang['so_luong'];
            $tongTien = $donHang['tong_tien'];
            $message =
                "<b>🛒 ĐƠN HÀNG MỚI</b>\n" .
                "Mã đơn: <b>$maDonHang</b>\n" .
                "Sản phẩm: $tenSanPham\n" .
                "Số lượng: $soLuong\n" .
                "Tổng tiền: <b>" . number_format($tongTien) . "đ (đã thanh toán)</b>";

            $this->telegramService->send($message);
        }
        echo json_encode([
            'status' => 'success',
            'message' => 'Thanh toán thành công',
            'don_hang_id' => $donHang['don_hang_id'],
            'so_tien' => $soTien
        ]);
    }
}
