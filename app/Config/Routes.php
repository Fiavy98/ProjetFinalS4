<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ClientController::landing');
$routes->get('/client', 'ClientController::index');
$routes->match(['get', 'post'], '/client/login', 'ClientController::login');
$routes->get('/client/logout', 'ClientController::logout');
$routes->get('/client/operations', 'ClientController::operations');
$routes->post('/client/depot', 'ClientController::depot');
$routes->post('/client/retrait', 'ClientController::retrait');
$routes->post('/client/transfert', 'ClientController::transfert');
$routes->get('/client/solde', 'ClientController::solde');
$routes->get('/client/historique', 'ClientController::historique');
$routes->match(['get', 'post'], '/client/transfertMultiple', 'ClientController::transfertMultiple');

$routes->get('/operateur', 'operateurControlleur::index');
$routes->match(['get', 'post'], '/operateur/login', 'operateurControlleur::login');
$routes->get('/operateur/logout', 'operateurControlleur::logout');
$routes->get('/operateur/profil', 'operateurControlleur::profil');
$routes->get('/operateur/operations', 'operateurControlleur::operations');
$routes->get('/operateur/frais', 'operateurControlleur::frais');
$routes->post('/operateur/frais', 'operateurControlleur::creerFrais');
$routes->get('/operateur/frais/(:num)/modifier', 'operateurControlleur::modifierFrais/$1');
$routes->post('/operateur/frais/(:num)/modifier', 'operateurControlleur::mettreAJourFrais/$1');
$routes->post('/operateur/frais/(:num)/supprimer', 'operateurControlleur::supprimerFrais/$1');
$routes->get('/operateur/gains', 'operateurControlleur::gains');
