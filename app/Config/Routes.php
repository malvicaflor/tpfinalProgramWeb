<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('productos', 'Productos::index');
$routes->get('/productos', 'Productos::index');
$routes->get('/productos/categoria/(:segment)', 'Productos::categoria/$1');
$routes->get('inspo', 'Inspo::index');
$routes->get('contact', 'Contact::index');
$routes->get('carrito', 'Carrito::index');
$routes->post('ajax_agregar', 'Carrito::ajax_agregar');
$routes->get('detalle/(:num)', 'Detalle::index/$1');
$routes->get('/carrito', 'Carrito::index');
$routes->get('/pagar', 'Carrito::pagar', ['filter' => 'auth']); 
$routes->get('usuarios/registro', 'Usuarios::registro');
$routes->post('usuarios/guardarRegistro', 'Usuarios::guardarRegistro');
$routes->get('usuarios/login', 'Usuarios::login');
$routes->post('usuarios/procesarLogin', 'Usuarios::procesarLogin');
$routes->get('usuarios/logout', 'Usuarios::logout');
$routes->post('carrito', 'Carrito::index');  
$routes->post('usuarios/login_post', 'Usuarios::login_post');
$routes->post('usuarios/registro_post', 'Usuarios::registro_post'); 
$routes->post('logout', 'Usuarios::logout');
$routes->post('carrito/procesarPago', 'Carrito::procesarPago');
$routes->post('carrito/procesar_pago', 'Carrito::procesarPago');
$routes->post('vaciar_carrito', 'Carrito::vaciarCarrito');
$routes->post('eliminar_producto', 'Carrito::eliminarProducto');
