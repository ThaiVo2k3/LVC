<?php
$routes->get('/', 'HomeController@index');
$routes->post('/login', 'HomeController@login');
$routes->post('/register', 'HomeController@register');
$routes->get('/logout', 'HomeController@logout');

$routes->get('/camera-quan-sat', 'ProductsController@index');
$routes->get('/camera-quan-sat/{slug}', 'ProductsController@detail');

$routes->get('/cart', 'CartsController@index');
$routes->post('/add-to-cart', 'CartsController@addToCart');
$routes->post('/cart/update-quantity', 'CartsController@updateQuantity');
$routes->post('/cart/remove-item', 'CartsController@removeFromCart');
$routes->post('/cart/prepare-checkout', 'CartsController@prepareCheckout');
$routes->post('/cart/checkout', 'CartsController@checkout');

$routes->get('/orders', 'OrdersController@index');
$routes->post('/orders/cancel', 'OrdersController@cancel');

$routes->get('/payments/{slug}', 'PaymentsController@index');
$routes->post('/payments/cancel', 'PaymentsController@cancel');
$routes->get('/payments/check-status', 'PaymentsController@checkStatus');

$routes->get('/api/webhook/bank', 'WebhooksController@handle');
$routes->post('/api/webhook/bank', 'WebhooksController@handle');

$routes->group('/admin', ['middleware' => ['auth', 'admin']], function ($routes) {
    $routes->get('/dashboard', 'admin/AdminDashboardsController@index');
    $routes->post('/change', 'admin/AdminDashboardsController@change');

    $routes->get('/brands', 'admin/AdminBrandsController@index');
    $routes->post('/brands/add', 'admin/AdminBrandsController@add');
    $routes->get('/brands/get/{id}', 'admin/AdminBrandsController@get');
    $routes->post('/brands/update/{id}', 'admin/AdminBrandsController@update');
    $routes->post('/brands/delete/{id}', 'admin/AdminBrandsController@delete');

    $routes->get('/categories', 'admin/AdminCategoriesController@index');
    $routes->post('/categories/add', 'admin/AdminCategoriesController@add');
    $routes->get('/categories/get/{id}', 'admin/AdminCategoriesController@get');
    $routes->post('/categories/update/{id}', 'admin/AdminCategoriesController@update');
    $routes->post('/categories/delete/{id}', 'admin/AdminCategoriesController@delete');

    $routes->get('/products', 'admin/AdminProductsController@index');
    $routes->post('/products/add', 'admin/AdminProductsController@add');
    $routes->get('/products/get/{id}', 'admin/AdminProductsController@get');
    $routes->post('/products/update/{id}', 'admin/AdminProductsController@update');
    $routes->post('/products/delete/{id}', 'admin/AdminProductsController@delete');

    $routes->get('/users', 'admin/AdminUsersController@index');
    $routes->get('/users/get/{id}', 'admin/AdminUsersController@get');

    $routes->get('/orders', 'admin/AdminOrdersController@index');
    $routes->get('/orders/get/{id}', 'admin/AdminOrdersController@get');
});
