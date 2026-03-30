<?php
class category extends CoreModels
{
    private $table = 'categories';

    public function __construct()
    {
        parent::__construct();
    }

    /* ================== SELECT ================== */

    public function getAllcategory()
    {
        return $this->getAll(
            "SELECT * FROM {$this->table} ORDER BY id DESC"
        );
    }

    public function getcategoryByid($id)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]   // ✅ bind param
        );
    }

    public function getcategoryBySlug($slug)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE slug = :slug",
            ['slug' => $slug]   // ✅ bind param
        );
    }

    /* ================== INSERT ================== */

    public function addcategory($data)
    {
        return $this->insert($this->table, $data);
    }

    /* ================== UPDATE ================== */

    public function updatecategory($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }

    /* ================== DELETE ================== */

    public function deletecategory($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
}
