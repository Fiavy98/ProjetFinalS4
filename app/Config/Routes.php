<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Hello::index');
$routes->get('/client', 'ClientController::index');
$routes->match(['get', 'post'], '/client/login', 'ClientController::login');
$routes->get('/client/logout', 'ClientController::logout');
$routes->get('/client/operations', 'ClientController::operations');
$routes->post('/client/depot', 'ClientController::depot');
$routes->post('/client/retrait', 'ClientController::retrait');
$routes->post('/client/transfert', 'ClientController::transfert');
$routes->get('/client/solde', 'ClientController::solde');
$routes->get('/client/historique', 'ClientController::historique');

$routes->get('/operateur', 'operateurControlleur::index');
$routes->get('/operateur/profil', 'operateurControlleur::profil');
$routes->get('/operateur/operations', 'operateurControlleur::operations');
$routes->get('/operateur/frais', 'operateurControlleur::frais');
$routes->get('/operateur/gains', 'operateurControlleur::gains');
