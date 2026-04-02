<?php
class PaymentsController extends BaseController
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    public function index($code)
    {
        $order = $this->orderModel->getOrderByCode($code);
        if (!$order) {
            $this->renderView('404');
            return;
        }

        if ($order['payment_status'] === 'Đã thanh toán') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Đã thanh toán',
                'redirect' => '/orders'
            ]);
            exit;
        }

        $this->renderView('payments', [
            'title' => 'Thanh toán',
            'layout' => 'main',
            'order' => $order
        ]);
    }

    public function cancel($code)
    {
        if (!$code) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid code']);
            return;
        }

        $order = $this->orderModel->getOrderByCode($code);
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'not_found']);
            return;
        }
        if ($order['payment_status'] === 'đã thanh toán') {
            echo json_encode(['success' => false, 'message' => 'đã thanh toán']);
            return;
        }
        $this->orderModel->updateOrder(['status' => 'đã hủy'], $order['id']);

        echo json_encode([
            'status' => 'canceled'
        ]);
    }
    public function checkStatus($code)
    {
        if (!$code) {
            echo json_encode(['success' => false, 'message' => 'Invalid code']);
            return;
        }

        $order = $this->orderModel->getOrderByCode($code);
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => $order['payment_status']
        ]);
    }
}
