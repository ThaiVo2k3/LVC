<?php
class Product extends CoreModels
{
    private $table = 'products';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllProduct()
    {
        return $this->getAll(
            "SELECT p.*, 
                b.name AS brand_name,
                c.name AS category_name
         FROM {$this->table} p
         LEFT JOIN brands b ON p.brand_id = b.id
         LEFT JOIN categories c ON p.category_id = c.id
         ORDER BY p.id DESC"
        );
    }
    public function getTopNew($limit)
    {
        return $this->getAll(
            "SELECT p.*, 
                b.name AS brand_name,
                c.name AS category_name
         FROM {$this->table} p
         LEFT JOIN brands b ON p.brand_id = b.id
         LEFT JOIN categories c ON p.category_id = c.id
         ORDER BY p.id DESC 
         LIMIT $limit"
        );
    }
    public function getTopCheap($limit)
    {
        return $this->getAll(
            "SELECT p.*, 
                b.name AS brand_name,
                c.name AS category_name
         FROM {$this->table} p
         LEFT JOIN brands b ON p.brand_id = b.id
         LEFT JOIN categories c ON p.category_id = c.id
         ORDER BY p.price ASC
         LIMIT $limit"
        );
    }
    public function getTopSell($limit)
    {
        return $this->getAll(
            "SELECT p.*, 
                b.name AS brand_name,
                c.name AS category_name,
                SUM(od.quantity) AS total_sold
         FROM {$this->table} p
         INNER JOIN order_details od ON p.id = od.product_id
         LEFT JOIN brands b ON p.brand_id = b.id
         LEFT JOIN categories c ON p.category_id = c.id
         GROUP BY p.id
         ORDER BY total_sold DESC
         LIMIT $limit"
        );
    }

    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Lấy sản phẩm theo id
     * @param int $id id của sản phẩm
     * @return array|null sản phẩm nếu có, null nếu không có
     */
    /*******  3490a1f4-ce03-40b0-97da-bf86cd2c49ca  *******/
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
            ['slug' => $slug]
        );
    }

    public function filterProducts(
        $search = '',
        $category_id = '',
        $brand_id = '',
        $min_price = '',
        $max_price = '',
        $sort = 'default',
        $limit = 12,
        $offset = 0
    ) {
        $sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name
            FROM {$this->table} p
            LEFT JOIN brands b ON p.brand_id = b.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE 1";

        $params = [];

        // search
        if (!empty($search)) {
            $sql .= " AND p.name LIKE :search";
            $params['search'] = "%$search%";
        }

        // category
        if (!empty($category_id)) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $category_id;
        }

        // brand
        if (!empty($brand_id)) {
            $sql .= " AND p.brand_id = :brand_id";
            $params['brand_id'] = $brand_id;
        }

        // price
        if ($min_price !== '') {
            $sql .= " AND p.price >= :min_price";
            $params['min_price'] = $min_price;
        }

        if ($max_price !== '') {
            $sql .= " AND p.price <= :max_price";
            $params['max_price'] = $max_price;
        }

        // SORT
        switch ($sort) {
            case 'price_asc':
                $order = 'p.price ASC';
                break;
            case 'price_desc':
                $order = 'p.price DESC';
                break;
            case 'newest':
            default:
                $order = 'p.created_at DESC';
                break;
        }

        // GẮN VÀO SQL (QUAN TRỌNG)
        $sql .= " ORDER BY $order";

        // pagination
        $sql .= " LIMIT $limit OFFSET $offset";

        return $this->getAll($sql, $params);
    }
    public function countProducts(
        $search = '',
        $category_id = '',
        $brand_id = '',
        $min_price = '',
        $max_price = ''
    ) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
            $params['search'] = "%$search%";
        }

        if (!empty($category_id)) {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $category_id;
        }

        if (!empty($brand_id)) {
            $sql .= " AND brand_id = :brand_id";
            $params['brand_id'] = $brand_id;
        }

        if ($min_price !== '') {
            $sql .= " AND price >= :min_price";
            $params['min_price'] = $min_price;
        }

        if ($max_price !== '') {
            $sql .= " AND price <= :max_price";
            $params['max_price'] = $max_price;
        }

        return $this->getOne($sql, $params)['total'];
    }

    public function addProduct($data)
    {
        return $this->insert($this->table, $data);
    }

    public function updateProduct($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }

    public function deleteProduct($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
}
