<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Pages::index');
$routes->get('login/index', 'Login::index');
$routes->post('/login/process', 'Login::process');
$routes->post('/login', 'Login::index');
$routes->get('/logout', 'Login::logout');
$routes->get('/kategori', 'Pages::kategori');
$routes->get('/produk', 'Pages::produk');
$routes->get('/laporan', 'Pages::laporan');
#postnyaKategoriDC
$routes->post('KategoriDC/getAllKategori', 'KategoriDC::getAllKategori');
$routes->post('KategoriDC/kategoriServerside', 'KategoriDC::kategoriServerside');
$routes->post('KategoriDC/addKategori', 'KategoriDC::addKategori');
$routes->post('KategoriDC/saveAddKategori', 'KategoriDC::saveAddKategori');
$routes->post('KategoriDC/deleteKategori', 'KategoriDC::deleteKategori');
$routes->post('KategoriDC/editKategori', 'KategoriDC::editKategori');
$routes->post('KategoriDC/saveEditKategori', 'KategoriDC::saveEditKategori');
#getnyaKategoriDC
$routes->get('KategoriDC/getAllKategori', 'KategoriDC::getAllKategori');
$routes->get('KategoriDC/kategoriServerside', 'KategoriDC::kategoriServerside');
$routes->get('KategoriDC/addKategori', 'KategoriDC::addKategori');
$routes->get('KategoriDC/saveAddKategori', 'KategoriDC::saveAddKategori');
$routes->get('KategoriDC/deleteKategori', 'KategoriDC::deleteKategori');
$routes->get('KategoriDC/editKategori', 'KategoriDC::editKategori');
$routes->get('KategoriDC/saveEditKategori', 'KategoriDC::saveEditKategori');
#ProdukDC
$routes->post('ProdukDC/getAllProduk', 'ProdukDC::getAllProduk');
$routes->post('ProdukDC/produkServerside', 'ProdukDC::produkServerside');
$routes->post('ProdukDC/addProduk', 'ProdukDC::addProduk');
$routes->post('ProdukDC/saveAddProduk', 'ProdukDC::saveAddProduk');
$routes->post('ProdukDC/deleteProduk', 'ProdukDC::deleteProduk');
$routes->post('ProdukDC/editProduk', 'ProdukDC::editProduk');
$routes->post('ProdukDC/saveEditProduk', 'ProdukDC::saveEditProduk');
##
$routes->get('ProdukDC/getAllProduk', 'ProdukDC::getAllProduk');
$routes->get('ProdukDC/produkServerside', 'ProdukDC::produkServerside');
$routes->get('ProdukDC/addProduk', 'ProdukDC::addProduk');
$routes->get('ProdukDC/saveAddProduk', 'ProdukDC::saveAddProduk');
$routes->get('ProdukDC/deleteProduk', 'ProdukDC::deleteProduk');
$routes->get('ProdukDC/editProduk', 'ProdukDC::editProduk');
$routes->get('ProdukDC/saveEditProduk', 'ProdukDC::saveEditProduk');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
