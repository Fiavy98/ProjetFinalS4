<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ClientController::landing');
$routes->get('/client', 'ClientController::index');
$routes->match(['get', 'post'], '/client/login', 'ClientController::login');
$routes->get('/client/logout', 'ClientController::logout');

$routes->group('client', ['filter' => 'clientauth'], static function ($routes) {
    $routes->get('operations', 'ClientController::operations');
    $routes->post('depot', 'ClientController::depot');
    $routes->post('retrait', 'ClientController::retrait');
    $routes->post('transfert', 'ClientController::transfert');
    $routes->get('solde', 'ClientController::solde');
    $routes->get('historique', 'ClientController::historique');
    $routes->match(['get', 'post'], 'transfertMultiple', 'ClientController::transfertMultiple');
});

$routes->get('/operateur', 'operateurControlleur::index', ['filter' => 'operateurauth']);
$routes->match(['get', 'post'], '/operateur/login', 'operateurControlleur::login');
$routes->get('/operateur/logout', 'operateurControlleur::logout');

$routes->group('operateur', ['filter' => 'operateurauth'], static function ($routes) {
    $routes->get('profil', 'operateurControlleur::profil');
    $routes->get('operations', 'operateurControlleur::operations');
    $routes->get('frais', 'operateurControlleur::frais');
    $routes->post('frais', 'operateurControlleur::creerFrais');
    $routes->get('frais/(:num)/modifier', 'operateurControlleur::modifierFrais/$1');
    $routes->post('frais/(:num)/modifier', 'operateurControlleur::mettreAJourFrais/$1');
    $routes->post('frais/(:num)/supprimer', 'operateurControlleur::supprimerFrais/$1');
    $routes->get('gains', 'operateurControlleur::gains');
    $routes->get('montants-a-envoyer', 'operateurControlleur::montantsAEnvoyer');
});
