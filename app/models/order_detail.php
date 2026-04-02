<?php
class Order_Details extends CoreModels
{
    private $table = 'order_details';
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllOrder_Details()
    {
        return $this->getAll("SELECT * FROM {$this->table} ORDER BY id DESC");
    }

    public function getAllOrder_DetailsByOrder_Id($id)
    {

        return $this->getAll(
            "SELECT 
            o.code AS order_code,
            p.name AS product_name,
            od.quantity,
            od.price

        FROM order_details od
        LEFT JOIN orders o ON od.order_id = o.id
        LEFT JOIN products p ON od.product_id = p.id

        WHERE od.order_id = $id"
        );
    }

    public function updateOrder_Details($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }
    public function deleteOrder_Details($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
    public function addOrder_Details($data)
    {
        return $this->insert($this->table, $data);
    }
}
