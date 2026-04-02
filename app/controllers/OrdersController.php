<?php
class OrdersController extends BaseController
{
    private $productModel;
    private $orderModel;
    private $orderDetailModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->orderDetailModel = new Order_Details();
    }
    public function index()
    {
        $user_id = $_SESSION['user']['id'];
        $orders = $this->orderModel->getAllOrderByUser($user_id);
        $this->renderView('orders', [
            'title' => 'Trang đơn hàng',
            'layout' => 'profile',
            'orders' => $orders
        ]);
    }
    public function detail($id)
    {
        $ok = $this->orderDetailModel->getAllOrder_DetailsByOrder_Id($id);
        if ($ok) {
            echo json_encode([
                'success' => true,
                'data' => $ok
            ]);
        }
    }
    function cancel($id)
    {
        $order = $this->orderModel->getOrderById($id);
        if ($order['status'] === 'đã hủy') {
            echo json_encode([
                'success' => false,
                'message' => 'đơn hàng đã hủy'
            ]);
            exit();
        }
        if ($order['payment_status'] === 'đã thanh toán') {
            echo json_encode([
                'success' => false,
                'message' => 'đơn hàng đã thanh toán'
            ]);
            exit();
        }
        $this->orderModel->updateOrder(['status' => 'đã hủy'], $id);
        echo json_encode([
            'success' => true,
            'message' => 'Hủy đơn hàng thành công'
        ]);
    }
}
