<?php
class CartsController extends BaseController
{
    private $productModel;
    private $orderModel;
    private $orderDetailModel;
    private $telegramService;

    public function __construct()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->orderDetailModel = new Order_Details();
        $this->telegramService = new TelegramService();
    }
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];

        $this->renderView('carts', [
            'title' => 'Trang giỏ hàng',
            'layout' => 'main',
            'cart' => $cart
        ]);
    }
    public function AddToCart()
    {
        $product_id = $_POST['product_id'] ?? null;

        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu product_id']);
            return;
        }

        $product = $this->productModel->getProductByid($product_id);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            return;
        }
        if (isset($_SESSION['cart'][$product_id])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sản phẩm đã có trong giỏ hàng'
            ]);
            return;
        }
        $_SESSION['cart'][$product_id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];

        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm vào giỏ',
            'cart_count' => count($_SESSION['cart'])
        ]);
    }

    public function updateQuantity()
    {
        $product_id = $_POST['product_id'] ?? null;
        $action = $_POST['action'] ?? null;

        if (!isset($_SESSION['cart'][$product_id])) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không có trong giỏ']);
            return;
        }

        if ($action === 'increase') {
            $_SESSION['cart'][$product_id]['quantity']++;
        } elseif ($action === 'decrease') {
            if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                $_SESSION['cart'][$product_id]['quantity']--;
            }
        }

        $count = count($_SESSION['cart']);

        echo json_encode([
            'success' => true,
            'cart' => $_SESSION['cart'],
            'count' => $count
        ]);
    }

    public function removeFromCart()
    {
        $product_id = $_POST['product_id'] ?? null;

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
        $count = count($_SESSION['cart']);

        echo json_encode([
            'success' => true,
            'message' => 'Đã xóa sản phẩm',
            'cart' => $_SESSION['cart'],
            'count' => $count
        ]);
    }
    public function checkout()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }

        if (empty($_POST['products'])) {
            echo json_encode(['success' => false, 'message' => 'Không có sản phẩm']);
            return;
        }

        $user_id = $_SESSION['user']['id'];
        $user_name = $_SESSION['user']['name'];

        $address = $_POST['address'];
        $payment = $_POST['payment'];
        $products = $_POST['products'];

        $total = 0;
        $items = [];

        foreach ($products as $product_id => $qty) {
            $product = $this->productModel->getProductByid($product_id);

            if (!$product) continue;

            $price = $product['price'];
            $total += $price * $qty;

            $items[] = [
                'product_id' => $product_id,
                'quantity' => $qty,
                'price' => $price
            ];
        }

        $code = 'DH' . generateID(8);

        $order_id = $this->orderModel->addOrder([
            'code' => $code,
            'user_id' => $user_id,
            'total_price' => $total,
            'address' => $address,
            'payment_method' => $payment
        ]);

        foreach ($items as $item) {
            $this->orderDetailModel->addOrder_Details([
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }
        if ($payment === 'cod') {

            $message = "<b>🛒 ĐƠN HÀNG MỚI</b>\n";
            $message .= "Mã đơn: <b>$code</b>\n";
            $message .= "Người đặt: <b>$user_name</b>\n";
            $message .= "----------------------\n";

            foreach ($items as $item) {
                $product = $this->productModel->getProductById($item['product_id']);

                if (!$product) continue;

                $name = $product['name'];
                $qty = $item['quantity'];
                $price = $item['price'];
                $subTotal = $price * $qty;

                $message .= "• Sản phẩm: $name\n";
                $message .= "  SL: $qty | Đơn giá: " . number_format($price) . "đ\n";
                $message .= "  Thành tiền: " . number_format($subTotal) . "đ\n";
                $message .= "----------------------\n";
            }

            $message .= "Tổng đơn: <b>" . number_format($total) . "đ</b>\n";
            $message .= "Địa chỉ: $address";

            $this->telegramService->send($message);
        }

        foreach ($products as $product_id => $qty) {
            unset($_SESSION['cart'][$product_id]);
        }

        $redirect = '/orders';
        if ($payment === 'online') {
            $redirect = '/payments/' . $code;
        }
        echo json_encode([
            'success' => true,
            'redirect' => $redirect
        ]);
        exit;
    }
}
