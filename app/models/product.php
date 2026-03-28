<?php
class Product extends CoreModels
{
    private $table = 'products';

    public function __construct()
    {
        parent::__construct();
    }

    /* ================== SELECT ================== */

    public function getAllProduct()
    {
        return $this->getAll(
            "SELECT p.*, b.name AS brand_name 
         FROM {$this->table} p
         LEFT JOIN brands b ON p.brand_id = b.id
         ORDER BY p.id DESC"
        );
    }
    public function getTopNew($limit)
    {
        return $this->getAll(
            "SELECT p.*, b.name AS brand_name 
         FROM {$this->table} p
         LEFT JOIN brands b ON p.brand_id = b.id
         ORDER BY p.id DESC 
         LIMIT $limit"
        );
    }
    public function getTopCheap($limit)
    {
        return $this->getAll(
            "SELECT p.*, b.name AS brand_name 
         FROM {$this->table} p
         LEFT JOIN brands b ON p.brand_id = b.id
         ORDER BY p.price ASC
         LIMIT $limit"
        );
    }
    public function getTopSell($limit)
    {
        return $this->getAll(
            "SELECT p.*, b.name AS brand_name, SUM(od.quantity) AS total_sold
        FROM {$this->table} p
        INNER JOIN order_details od ON p.id = od.product_id
        LEFT JOIN brands b ON p.brand_id = b.id
        GROUP BY p.id
        ORDER BY total_sold DESC
        LIMIT $limit"
        );
    }

    public function getProductByid($id)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]   // ✅ bind param
        );
    }

    public function getProductBySlug($slug)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE slug = :slug",
            ['slug' => $slug]   // ✅ bind param
        );
    }

    /* ================== INSERT ================== */

    public function addProduct($data)
    {
        return $this->insert($this->table, $data);
    }

    /* ================== UPDATE ================== */

    public function updateProduct($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }

    /* ================== DELETE ================== */

    public function deleteProduct($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
}
