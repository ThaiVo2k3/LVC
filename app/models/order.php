<?php
class Order extends CoreModels
{
    private $table = 'orders';
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllOrder()
    {
        return $this->getAll(
            "SELECT p.*, b.name AS user_name 
         FROM {$this->table} p
         LEFT JOIN users b ON p.user_id = b.id
         ORDER BY p.id DESC"
        );
    }

    public function getOrderById($id)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    public function updateOrder($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }
    public function deleteOrder($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
    public function addOrder($data)
    {
        return $this->insert($this->table, $data);
    }
}
