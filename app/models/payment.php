<?php
class Payment extends CoreModels
{
    private $table = 'payments';
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllPayment()
    {
        return $this->getAll(
            "SELECT p.*, b.name AS user_name 
        FROM {$this->table} p
        LEFT JOIN users b ON p.user_id = b.id
        ORDER BY p.id DESC"
        );
    }
    public function getRevenueLast7Days()
    {
        return $this->getAll("
        SELECT DATE(created_at) as date, SUM(amount) as total
        FROM payments
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    }
    public function getPaymentById($id)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }
    public function getPaymentBytransaction_code($code)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE transaction_code = :transaction_code",
            ['transaction_code' => $code]
        );
    }
    public function getPaymentByPhone($phone)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE phone = :phone",
            ['phone' => $phone]
        );
    }
    public function updatePayment($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }
    public function deletePayment($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
    public function getPaymentByRole($role)
    {
        return $this->getAll("SELECT * FROM {$this->table} WHERE role = '$role'");
    }
    public function addPayment($data)
    {
        return $this->insert($this->table, $data);
    }
}
