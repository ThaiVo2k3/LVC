<?php
class AdminDashboardsController extends BaseController
{
    private $userModel;
    private $productModel;
    private $orderModel;
    private $paymentModel;
    public function __construct()
    {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->paymentModel = new Payment();
    }
    public function index()
    {
        $this->renderView('admin/Dashboard', [
            'title' => 'trang thống kê',
            'layout' => 'admin',
            'totalUsers'    => $this->userModel->getAllUser(),
            'totalOrders'   => $this->orderModel->getAllOrder(),
            'totalProducts' => $this->productModel->getAllproduct(),
            'totalPayments'  => $this->paymentModel->getAllPayment(),
            'todayRevenue'  => $this->paymentModel->getRevenueLast7Days()
        ]);
    }
    public function change()
    {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'error' => 'Invalid request method'
            ]);
            exit;
        }

        $id = $_SESSION['user']['id'];
        $matkhaucu = $_POST['current_password'] ?? '';
        $matkhaumoi = $_POST['new_password'] ?? '';
        $xatnhan = $_POST['confirm_password'] ?? '';
        $matkhauhientai = $this->userModel->getPassById($id);

        if (!password_verify($matkhaucu, $matkhauhientai['password'])) {

            echo json_encode(
                [
                    "success" => false,
                    "message" => 'Mật khẩu cũ không đúng'
                ]
            );
            exit;
        } elseif ($matkhaumoi !== $xatnhan) {
            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Mật khẩu mới không khớp!'
                ]
            );
            exit;
        } elseif (password_verify($matkhaumoi, $matkhauhientai['password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Mật khẩu mới không được trùng mật khẩu hiện tại'
            ]);
            exit;
        } else {
            $hashed = password_hash($matkhaumoi, PASSWORD_BCRYPT);
            $data = [
                'password' => $hashed
            ];
            $this->userModel->updateUser($data, $id);
            echo json_encode([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công!',
                'redirect' => '/logout'
            ]);
            exit;
        }
    }
}
