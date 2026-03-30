<?php
class AdminProductsController extends BaseController
{
    private $fileService;
    private $productModel;
    private $brandModel;
    private $categoryModel;
    public function __construct()
    {

        $this->fileService = new FileUploadService();
        $this->productModel = new product();
        $this->brandModel = new brand();
        $this->categoryModel = new category();
    }
    public function index()
    {
        $products = $this->productModel->getAllproduct();
        $brands = $this->brandModel->getAllbrand();
        $categories = $this->categoryModel->getAllcategory();
        $this->renderView('admin/products', [
            'title' => 'Danh sách sản phẩm',
            'layout' => 'admin',
            'products' => $products,
            'brands' => $brands,
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
        $res = $this->productModel->getproductById($id);
        if (!$res) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy sản phẩm"
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
        $price = trim($_POST['price'] ?? '');
        $category_id = trim($_POST['category_id'] ?? '');
        $brand_id = trim($_POST['brand_id'] ?? '');
        $camera_resolution = trim($_POST['resolution'] ?? '');
        $camera_fps = trim($_POST['fps'] ?? '');
        $camera_lens = trim($_POST['lens'] ?? '');

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
            "price" => $price,
            "category_id" => $category_id,
            "brand_id" => $brand_id,
            "camera_resolution" => $camera_resolution,
            "camera_fps" => $camera_fps,
            "camera_lens" => $camera_lens,
            "image" => null
        ];
        $this->productModel->addproduct($data);
        $id = $this->productModel->lastID();
        $uploadDir = "public/uploads/products/$id/";
        $imgName = null;
        if (!empty($_FILES['image']['name'])) {
            $validation = $this->fileService->validateFile($_FILES['image']);
            if (!$validation['valid']) {
                $this->productModel->deleteproduct($id);
                echo json_encode([
                    "success" => false,
                    "error"   => "Validation failed",
                    "message" => $validation['error']
                ]);
                return;
            }
            $uploadResult = $this->fileService->uploadFile($_FILES['image'], $uploadDir);
            if (!$uploadResult['success']) {
                $this->productModel->deleteproduct($id);
                echo json_encode([
                    "success" => false,
                    "error"   => "Upload failed",
                    "message" => $uploadResult['error']
                ]);
                return;
            }
            $imgName = $uploadResult['fileName'];
            $this->productModel->updateproduct(["image" => $imgName], $id);
        }
        echo json_encode([
            "success" => true,
            "message" => "Thêm sản phẩm thành công",
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

        $old = $this->productModel->getproductById($id);
        if (!$old) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy sản phẩm có ID = $id"
            ]);
            return;
        }
        $oldImg = $old['image'];
        $uploadDir = "public/uploads/products/$id/";
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
        $price = trim($_POST['price'] ?? '');
        $category_id = trim($_POST['category_id'] ?? '');
        $brand_id = trim($_POST['brand_id'] ?? '');
        $camera_resolution = trim($_POST['resolution'] ?? '');
        $camera_fps = trim($_POST['fps'] ?? '');
        $camera_lens = trim($_POST['lens'] ?? '');

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
            "image" => $imgName,
            "price" => $price,
            "category_id" => $category_id,
            "brand_id" => $brand_id,
            "camera_resolution" => $camera_resolution,
            "camera_fps" => $camera_fps,
            "camera_lens" => $camera_lens
        ];

        $ok = $this->productModel->updateproduct($data, $id);
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

        $old = $this->productModel->getproductById($id);
        if (!$old) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy sản phẩm"
            ]);
            return;
        }
        $uploadDir = "public/uploads/products/$id/";
        $ok = $this->productModel->deleteproduct($id);
        if ($ok) {
            if (!empty($old['image'])) {
                $this->fileService->deleteFile($uploadDir . $old['image']);
            }
            $this->fileService->deleteDirectory($uploadDir, true);
        }
        echo json_encode([
            "success" => $ok ? true : false,
            "error"   => $ok ? null : "Delete failed",
            "message" => $ok ? "Xóa sản phẩm thành công" : "Xóa thất bại"
        ]);
    }
}
