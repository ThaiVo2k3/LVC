<?php
class Brand extends CoreModels
{
    private $table = 'brands';

    public function __construct()
    {
        parent::__construct();
    }

    /* ================== SELECT ================== */

    public function getAllBrand()
    {
        return $this->getAll(
            "SELECT * FROM {$this->table} ORDER BY id DESC"
        );
    }

    public function getBrandByid($id)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]   // ✅ bind param
        );
    }

    public function getBrandBySlug($slug)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE slug = :slug",
            ['slug' => $slug]   // ✅ bind param
        );
    }

    /* ================== INSERT ================== */

    public function addBrand($data)
    {
        return $this->insert($this->table, $data);
    }

    /* ================== UPDATE ================== */

    public function updateBrand($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }

    /* ================== DELETE ================== */

    public function deleteBrand($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
}
