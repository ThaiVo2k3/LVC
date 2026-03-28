<?php
class AdminBrandsController extends BaseController
{
    private $fileService;
    private $brandModel;
    public function __construct()
    {

        $this->fileService = new FileUploadService();
        $this->brandModel = new brand();
    }
    public function index()
    {
        $brands = $this->brandModel->getAllbrand();
        $this->renderView('admin/brands', [
            'title' => 'Danh sách danh mục',
            'layout' => 'admin',
            'brands' => $brands
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
        $res = $this->brandModel->getbrandById($id);
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

        $errors = [];

        if ($name === '') {
            $errors['name'] = "Tên danh mục không được để trống";
        } elseif (mb_strlen($name) < 3) {
            $errors['name'] = "Tên phải ít nhất 3 ký tự";
        }

        if (!empty($errors)) {
            echo json_encode([
                "success" => false,
                "errors"  => $errors
            ]);
            return;
        }

        $data = [
            "name" => $name,
            "image" => null
        ];
        $this->brandModel->addbrand($data);
        $id = $this->brandModel->lastID();
        $uploadDir = "public/uploads/brands/$id/";
        $imgName = null;
        if (!empty($_FILES['image']['name'])) {
            $validation = $this->fileService->validateFile($_FILES['image']);
            if (!$validation['valid']) {
                $this->brandModel->deletebrand($id);
                echo json_encode([
                    "success" => false,
                    "error"   => "Validation failed",
                    "message" => $validation['error']
                ]);
                return;
            }
            $uploadResult = $this->fileService->uploadFile($_FILES['image'], $uploadDir);
            if (!$uploadResult['success']) {
                $this->brandModel->deletebrand($id);
                echo json_encode([
                    "success" => false,
                    "error"   => "Upload failed",
                    "message" => $uploadResult['error']
                ]);
                return;
            }
            $imgName = $uploadResult['fileName'];
            $this->brandModel->updatebrand(["image" => $imgName], $id);
        }
        echo json_encode([
            "success" => true,
            "message" => "Thêm danh mục thành công",
            "id"      => $id,
            "image"   => $imgName
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

        $old = $this->brandModel->getbrandById($id);
        if (!$old) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy danh mục có ID = $id"
            ]);
            return;
        }
        $oldImg = $old['image'];
        $uploadDir = "public/uploads/brands/$id/";
        $imgName = $oldImg;
        if (!empty($_FILES['anh_new']['name'])) {
            $validation = $this->fileService->validateFile($_FILES['anh_new']);
            if (!$validation['valid']) {
                echo json_encode([
                    "success" => false,
                    "error"   => "Validation failed",
                    "message" => $validation['error']
                ]);
                return;
            }
            $uploadResult = $this->fileService->uploadFile(
                $_FILES['anh_new'],
                $uploadDir,
                $oldImg
            );
            if (!$uploadResult['success']) {
                echo json_encode([
                    "success" => false,
                    "error"   => "Upload failed",
                    "message" => $uploadResult['error']
                ]);
                return;
            }
            $imgName = $uploadResult['fileName'];
        }

        $name = trim($_POST['name'] ?? '');

        $errors = [];

        // Validate name
        if ($name === '') {
            $errors['name'] = "Tên danh mục không được để trống";
        } elseif (mb_strlen($name) < 3) {
            $errors['name'] = "Tên phải ít nhất 3 ký tự";
        }

        if (!empty($errors)) {
            echo json_encode([
                "success" => false,
                "errors"  => $errors
            ]);
            return;
        }
        $data = [
            "name" => $name,
            "image" => $imgName
        ];

        $ok = $this->brandModel->updatebrand($data, $id);
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

        $old = $this->brandModel->getbrandById($id);
        if (!$old) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy danh mục"
            ]);
            return;
        }
        $uploadDir = "public/uploads/brands/$id/";
        $ok = $this->brandModel->deletebrand($id);
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
