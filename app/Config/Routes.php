<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Hello::index');

$routes->get('operateur', 'operateurControlleur::index');
$routes->get('operateur/profil', 'operateurControlleur::profil');
$routes->get('operateur/operations', 'operateurControlleur::operations');
$routes->get('operateur/frais', 'operateurControlleur::frais');
$routes->get('operateur/gains', 'operateurControlleur::gains');
