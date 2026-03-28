<?php
class HomeController extends BaseController
{
    private $usersModel;
    private $productsModel;
    private $brandsModel;
    public function __construct()
    {
        $this->usersModel = new User();
        $this->productsModel = new Product();
        $this->brandsModel = new Brand();
    }
    public function index()
    {
        $top_new = $this->productsModel->getTopNew(4);
        $top_cheap = $this->productsModel->getTopCheap(4);
        $top_sell = $this->productsModel->getTopSell(4);
        $brands = $this->brandsModel->getAllbrand();
        $this->renderView("index", [
            'title' => 'home',
            'layout' => 'main',
            'top_new' => $top_new,
            'top_cheap' => $top_cheap,
            'top_sell' => $top_sell,
            'brands' => $brands
        ]);
    }
    public function login()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $phone = trim($_POST['sodienthoai'] ?? '');
        $password = $_POST['matkhau'] ?? '';

        if (empty($phone) || empty($password)) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $user = $this->usersModel->getUserByPhone($phone);

        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'Số điện thoại không tồn tại'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        if (!password_verify($password, $user['password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Mật khẩu không chính xác'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        $redirect = ($user['role'] !== 'customer') ? '/admin/dashboard' : '/';

        echo json_encode([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'redirect' => $redirect
        ], JSON_UNESCAPED_UNICODE);
    }
    public function register()
    {
        header('Content-Type: application/json; charset=utf-8');

        // Kiểm tra request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Phương thức không hợp lệ'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Lấy dữ liệu từ POST
        $hoten = trim($_POST['ho_ten'] ?? '');
        $sdt = trim($_POST['so_dien_thoai'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $matkhau = $_POST['mat_khau'] ?? '';
        $xatnhan = $_POST['xac_nhan'] ?? '';
        if ($matkhau !== $xatnhan) {
            echo json_encode([
                'success' => false,
                'message' => 'Mật khẩu không khớp'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }
        // Kiểm tra số điện thoại có tồn tại trong DB
        $user = $this->usersModel->getUserByPhone($sdt);

        if ($user) {
            echo json_encode([
                'success' => false,
                'message' => 'Số điện thoại không tồn tại'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $hashed = password_hash($matkhau, PASSWORD_BCRYPT);
        $data = [
            'name' => $hoten,
            'phone' => $sdt,
            'email' => $email,
            'password' => $hashed
        ];

        $this->usersModel->addUser($data);
        $id = $this->usersModel->lastID();
        $user = $this->usersModel->getUserById($id);
        // Đăng nhập thành công - Lưu session
        session_regenerate_id(true);

        $_SESSION['user'] = $user;

        $redirect = ($user['role'] !== 'member') ? '/admin/dashboard' : '/';

        echo json_encode([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'redirect' => $redirect
        ], JSON_UNESCAPED_UNICODE);
    }
    public function logout()
    {
        session_destroy();
        header("Location: /");
        exit();
    }
}
