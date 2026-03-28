<?php
$routes->get('/', 'HomeController@index');
$routes->post('/login', 'HomeController@login');
$routes->post('/register', 'HomeController@register');
$routes->get('/logout', 'HomeController@logout');

$routes->group('/admin', ['middleware' => ['auth', 'admin']], function ($routes) {
    $routes->get('/dashboard', 'admin/AdminDashboardsController@index');
    $routes->post('/change', 'admin/AdminDashboardsController@change');

    $routes->get('/brands', 'admin/AdminBrandsController@index');
    $routes->post('/brands/add', 'admin/AdminBrandsController@add');
    $routes->get('/brands/get/{id}', 'admin/AdminBrandsController@get');
    $routes->post('/brands/update/{id}', 'admin/AdminBrandsController@update');
    $routes->post('/brands/delete/{id}', 'admin/AdminBrandsController@delete');

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
