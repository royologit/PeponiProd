<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$admin_softlink       = 'dashboard/';
$admin_dir_controller = $this->config->item('admin_dir_controller');

$route = array(
    'home'                                        => 'GeneralController',
    'default_controller'                          => 'GeneralController',
    'package/(:any)'                              => 'GeneralController/package/$1',
    'package/(:any)/order-open-trip'              => 'Order/order_open_step_1/$1',
    'package/(:any)/order-private-trip'           => 'Order/order_private/$1',
    'package/(:any)/order-open-trip/review'       => 'Order/order_open_step_2/$1',
    'package/(:any)/order-open-trip/finish'       => 'Order/order_finish/1',
    'package/(:any)/order-private-trip/finish'    => 'Order/order_finish/2',
    'filter/(:any)'                               => 'GeneralController/filter/$1',
    'packageSearch'                               => 'GeneralController/packageSearch',
    $admin_softlink.'login'                       => $admin_dir_controller.'admin/AuthController/login',
    $admin_softlink.'logout'                      => $admin_dir_controller.'admin/AuthController/logout',
    $admin_softlink.'v2/(:any)'                   => $admin_dir_controller.'admin/SystemController/$1',
    $admin_softlink.'(:any)'                      => $admin_dir_controller.'admin/AdminController/page/$1',
    $admin_softlink.'Voucher_Management/add'      => $admin_dir_controller.'admin/VoucherController/add',
    $admin_softlink.'Voucher_Management/edit/(:any)'=> $admin_dir_controller.'admin/VoucherController/edit/$1',
    $admin_softlink.'(:any)/add'                  => $admin_dir_controller.'admin/AdminController/update_form/$1/add',
    $admin_softlink.'(:any)/edit/(:any)'          => $admin_dir_controller.'admin/AdminController/update_form/$1/edit/$2',
    $admin_softlink.'(:any)/delete/(:any)'        => $admin_dir_controller.'admin/AdminController/delete/$1/$2',
    $admin_softlink.'(:any)/add_images/(:any)'    => $admin_dir_controller.'admin/AdminController/add_product_image/$2',
    $admin_softlink.'(:any)/add_images/(:any)'    => $admin_dir_controller.'admin/AdminController/add_product_image/$2',
    $admin_softlink.'package/push/(:any)/(:any)'    => $admin_dir_controller.'admin/AdminController/pushPackage/$1/$2',
    $admin_softlink.'product/push/(:any)/(:any)'    => $admin_dir_controller.'admin/AdminController/productPackage/$1/$2',
    '404_override'                  => '',
    'translate_uri_dashes'          => FALSE,
    'terms-conditions'                             => 'GeneralController/tnc',
    'payments'                                     => 'GeneralController/getAllPaymentMethod',
    'testingEmail'                                 => 'GeneralController/testingEmail',
    'payment/verifikasi'                           => 'GeneralController/verifikasi',
    'pembayaran/(:any)'                            => 'GeneralController/checkout/$1',
    'proses_pembayaran'                            => 'GeneralController/proses_pembayaran',
);
