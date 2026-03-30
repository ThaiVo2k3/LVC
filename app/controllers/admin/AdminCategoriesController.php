<?php
class AdminCategoriesController extends BaseController
{
    private $fileService;
    private $categoryModel;
    public function __construct()
    {

        $this->fileService = new FileUploadService();
        $this->categoryModel = new category();
    }
    public function index()
    {
        $categories = $this->categoryModel->getAllcategory();
        $this->renderView('admin/categories', [
            'title' => 'Danh sách danh mục',
            'layout' => 'admin',
            'categories' => $categories
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
        $res = $this->categoryModel->getcategoryById($id);
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
    public function add()
    {
        header("Content-Type: application/json; charset=utf-8");
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "success" => false,
                "error"   => "Invalid method",
                "message" => "Yêu cầu phải là POST"
            ]);
            return;
        }
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            echo json_encode([
                "success" => false,
                "message" => "Tên danh mục không được để trống"
            ]);
            return;
        }

        if (mb_strlen($name) < 3) {
            echo json_encode([
                "success" => false,
                "message" => "Tên phải ít nhất 3 ký tự"
            ]);
            return;
        }

        $data = [
            "name" => $name
        ];
        $this->categoryModel->addcategory($data);
        echo json_encode([
            "success" => true,
            "message" => "Thêm danh mục thành công",
        ]);
    }
    public function update($id)
    {
        header("Content-Type: application/json; charset=utf-8");
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "success" => false,
                "error" => "Invalid method",
                "message" => "Yêu cầu phải là POST"
            ]);
            return;
        }

        $old = $this->categoryModel->getcategoryById($id);
        if (!$old) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy danh mục có ID = $id"
            ]);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            echo json_encode([
                "success" => false,
                "message" => "Tên danh mục không được để trống"
            ]);
            return;
        }

        if (mb_strlen($name) < 3) {
            echo json_encode([
                "success" => false,
                "message" => "Tên phải ít nhất 3 ký tự"
            ]);
            return;
        }
        $data = [
            "name" => $name,
        ];

        $ok = $this->categoryModel->updatecategory($data, $id);
        echo json_encode([
            "success" => $ok ? true : false,
            "error"   => $ok ? null : "Update failed",
            "message" => $ok ? "Cập nhật thành công" : "Cập nhật thất bại"
        ]);
    }
    public function delete($id)
    {
        header("Content-Type: application/json; charset=utf-8");
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "success" => false,
                "error" => "Invalid method",
                "message" => "Yêu cầu phải là POST"
            ]);
            return;
        }

        $old = $this->categoryModel->getcategoryById($id);
        if (!$old) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy danh mục"
            ]);
            return;
        }
        $uploadDir = "public/uploads/categories/$id/";
        $ok = $this->categoryModel->deletecategory($id);
        if ($ok) {
            if (!empty($old['image'])) {
                $this->fileService->deleteFile($uploadDir . $old['image']);
            }
            $this->fileService->deleteDirectory($uploadDir, true);
        }
        echo json_encode([
            "success" => $ok ? true : false,
            "error"   => $ok ? null : "Delete failed",
            "message" => $ok ? "Xóa danh mục thành công" : "Xóa thất bại"
        ]);
    }
}
