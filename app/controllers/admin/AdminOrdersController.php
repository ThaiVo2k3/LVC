<?php
class AdminOrdersController extends BaseController
{
    private $fileService;
    private $orderModel;
    private $order_delailsModel;
    public function __construct()
    {

        $this->fileService = new FileUploadService();
        $this->orderModel = new Order();
        $this->order_delailsModel = new Order_Details();
    }
    public function index()
    {
        $orders = $this->orderModel->getAllOrder();
        $this->renderView('admin/orders', [
            'title' => 'Danh sách đơn hàng',
            'layout' => 'admin',
            'orders' => $orders
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
        $res = $this->order_delailsModel->getAllOrder_DetailsByOrder_Id($id);
        if (!$res) {
            echo json_encode([
                "success" => false,
                "error" => "Not found",
                "message" => "Không tìm thấy đơn hàng"
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
