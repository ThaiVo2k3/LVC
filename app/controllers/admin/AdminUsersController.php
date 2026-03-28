<?php
class AdminUsersController extends BaseController
{
    private $fileService;
    private $userModel;
    public function __construct()
    {

        $this->fileService = new FileUploadService();
        $this->userModel = new user();
    }
    public function index()
    {
        $users = $this->userModel->getAllUser();
        $this->renderView('admin/users', [
            'title' => 'Danh sách khách hàng',
            'layout' => 'admin',
            'users' => $users
        ]);
    }
    public function get($id)
    {
        header("Content-Type: application/json; charset=utf-8");
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode([
                "success" => false,
                "error"   => "Invalid method",
                "message" => "Yêu cầu phải là GET"
            ]);
            return;
        }
        $res = $this->userModel->getUserById($id);
        if (!$res) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy danh mục"
            ]);
            return;
        }
        echo json_encode([
            "success" => true,
            "message" => "Lấy dữ liệu thành công",
            "data" => $res
        ]);
    }
}
