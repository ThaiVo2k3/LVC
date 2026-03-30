<?php
class ProductsController extends BaseController
{
    private $productModel;
    private $categoryModel;
    private $brandModel;


    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->brandModel = new Brand();
    }

    public function index()
    {

        $page = $_GET['page'] ?? 1;
        $limit = 4;
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->filterProducts(
            $_GET['search'] ?? '',
            $_GET['category'] ?? '',
            $_GET['brand'] ?? '',
            $_GET['min_price'] ?? '',
            $_GET['max_price'] ?? '',
            $_GET['sort'] ?? 'newest',
            $limit,
            $offset
        );

        $total = $this->productModel->countProducts(
            $_GET['search'] ?? '',
            $_GET['category'] ?? '',
            $_GET['brand'] ?? '',
            $_GET['min_price'] ?? '',
            $_GET['max_price'] ?? ''
        );

        $totalPages = ceil($total / $limit);

        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category'] ?? '';
        $brand_id = $_GET['brand'] ?? '';

        $categories = $this->categoryModel->getAllCategory();
        $brands = $this->brandModel->getAllBrand();

        $this->renderView('products', [
            'title' => 'Danh sách Sản phẩm',
            'layout' => 'main',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'search' => $search,
            'category_id' => $category_id,
            'brand_id' => $brand_id,
            'totalPages' => $totalPages,
            'page' => $page
        ]);
    }

    public function detail($slug)
    {
        $id = layIdTuSlug($slug);

        if (!$id) {
            exit('Sản phẩm không tồn tại');
        }
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            exit('Sản phẩm không tồn tại');
        }
        $this->renderView('details', [
            'title' => 'Chi tiết sản phẩm',
            'layout' => 'main',
            'product' => $product
        ]);
    }
}
