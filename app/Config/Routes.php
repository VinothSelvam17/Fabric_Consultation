<?php

namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('TextileController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('/', 'TextileController::index');
$routes->get('textile', 'TextileController::index');
$routes->post('textile/addItem', 'TextileController::addItem');
$routes->post('textile/addColour', 'TextileController::addColour');
$routes->post('textile/addDcEntry', 'TextileController::addDcEntry');
$routes->get('textile/viewItem/(:num)', 'TextileController::viewItem/$1');
$routes->post('textile/groupByWidth', 'TextileController::groupByWidth');
$routes->post('textile/groupByInternal', 'TextileController::groupByInternal');
$routes->post('textile/groupByShade', 'TextileController::groupByShade');

$routes->get('textile/editDcEntry/(:num)', 'TextileController::editDcEntry/$1');
$routes->post('textile/updateDcEntry/(:num)', 'TextileController::updateDcEntry/$1');
$routes->get('textile/deleteDcEntry/(:num)', 'TextileController::deleteDcEntry/$1');
$routes->get('textile/deleteItem/(:num)', 'TextileController::deleteItem/$1');
$routes->get('textile/deleteColour/(:num)', 'TextileController::deleteColour/$1');


if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}