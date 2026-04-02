<?php

class WebhooksController extends BaseController
{
    protected $paymentModel;
    protected $orderModel;
    private $orderDetailModel;
    private $userModel;
    private $telegramService;
    private $productModel;

    public function __construct()
    {

        $this->paymentModel = new Payment();
        $this->orderModel = new Order();
        $this->orderDetailModel = new Order_Details();
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
                'success' => false,
                'message' => 'Invalid API key'
            ]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));
        if (!is_object($data)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'No data'
            ]);
            return;
        }

        $transaction_code = $data->referenceCode ?? '';
        $content    = trim($data->content ?? '');
        $amount     = (int)($data->transferAmount ?? 0);

        preg_match('/\bDH[A-Z0-9]{8}\b/i', $content, $matches);

        if (empty($matches)) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy mã đơn hàng trong nội dung'
            ]);
            return;
        }

        $code = $matches[0];

        $order = $this->orderModel->getOrderByCode($code);

        if (!$order) {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ]);
            return;
        }

        if ($order['payment_status'] === 'đã thanh toán') {
            echo json_encode([
                'success' => false,
                'message' => 'Đơn hàng đã được thanh toán'
            ]);
            return;
        }
        $payment = $this->paymentModel->getPaymentBytransaction_code($transaction_code);
        if ($payment) {
            echo json_encode([
                'success' => false,
                'message' => 'Giao dịch đã xử lý'
            ]);
            return;
        }
        $total_price = (int)$order['total_price'];

        if ($amount !== $total_price) {
            echo json_encode([
                'success' => false,
                'message' => 'Số tiền không khớp',
                'so_tien_bank' => $amount,
                'tong_tien_don_hang' => $total_price
            ]);
            return;
        }
        $user_id = $order['user_id'];
        $user = $this->userModel->getUserById($user_id);
        $user_name = $user['name'];
        $data_insert = [
            'user_id' => $user_id,
            'amount'       => $amount,
            'transaction_code'  => $transaction_code,
            'content'      => $content,
            'sender_name'     => $data->accountNumber ?? '',
            'request_time'     => date('Y-m-d H:i:s')
        ];
        $ok = $this->paymentModel->addPayment($data_insert);
        if ($ok) {
            $message =
                "<b>🛒 NHẬN TIỀN</b>\n" .
                "Mã đơn hàng: <b>$code</b>\n" .
                "Họ tên: <b>$user_name</b>\n" .
                "Số tiền: <b>+ " . number_format($amount) . "đ</b>";

            $this->telegramService->send($message);
        }

        $ok = $this->orderModel->updateOrder(['payment_status' => 'đã thanh toán'], $order['id']);
        if ($ok) {

            $message = "<b>🛒 ĐƠN HÀNG MỚI</b>\n";
            $message .= "Mã đơn: <b>$code</b>\n";
            $message .= "Người đặt: <b>$user_name</b>\n";
            $message .= "----------------------\n";
            $items = $this->orderDetailModel->getAllOrder_DetailsByOrder_Id($order['id']);
            foreach ($items as $item) {
                $name = htmlspecialchars($item['product_name'] ?? 'Không rõ');
                $qty = (int)$item['quantity'];
                $price = (int)$item['price'];
                $subTotal = $price * $qty;

                $message .= "• Sản phẩm: {$name}\n";
                $message .= "  SL: {$qty} | Đơn giá: " . number_format($price) . "đ\n";
                $message .= "  Thành tiền: " . number_format($subTotal) . "đ\n";
                $message .= "----------------------\n";
            }

            $message .= "Tổng đơn: <b>" . number_format($order['total_price']) . "đ</b>\n";
            $message .= "Địa chỉ: $order[address]\n";

            $this->telegramService->send($message);
        }
        echo json_encode([
            'success' => true,
            'message' => 'Thanh toán thành công',
            'id' => $order['id'],
            'amount' => $amount
        ]);
    }
}
