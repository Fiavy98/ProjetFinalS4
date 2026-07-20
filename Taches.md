# Version 1

# TODO LISTE

## [ok] Base de donnees

### Table operateur
- id
- nom
- prefixes

### Table typeOperation
- id
- libele

### Table operation
- id
- idTypeOperation
- idClient
- valeur
- idFrais
- description
- dateheure

### Table frais
- id
- idTypeOperation
- min
- max
- valeur

### Table gain
- id
- idOperateur
- idHistorique
- valeur

### Table historiqueGain
- id
- dateheure
- idOperation
- valeur

### Table client
- id
- num
- mdp
- nom
- solde

### Table historiqueOperationClient
- id
- idClient
- idOperation
- dateheure


---

# Cote operateur

## Tache 1 : Configuration et verification des prefixes

- [OK] Recuperer les prefixes depuis la table operateur
- [OK] Separer les differents prefixes stockes
    - Exemple : 034,038 devient 034 et 038
- [OK] Nettoyer le numero entre
    - Suppression des espaces et caracteres inutiles
- [OK] Verifier si le numero commence par un prefixe configure
- [OK] Si le prefixe existe
    - Retourner l'operateur correspondant
- [OK] Sinon
    - Retourner une erreur
        - Numero invalide
        - Operateur inconnu


---

## Tache 2 : Gestion des operations avec frais

### Depot avec frais obligatoire

- [OK] Recevoir une demande de depot du client
- [OK] Identifier le type d'operation Depot
- [OK] Rechercher le frais correspondant dans la table frais
- [OK] Trouver la tranche de montant correspondante
    - min
    - max
- [OK] Appliquer obligatoirement le frais
- [OK] Enregistrer l'operation avec son idFrais


### Retrait avec frais obligatoire

- [OK] Recevoir une demande de retrait du client
- [OK] Verifier que le solde du client est suffisant
- [OK] Identifier le type d'operation Retrait
- [OK] Rechercher le frais correspondant au montant
- [OK] Appliquer obligatoirement le frais
- [OK] Enregistrer l'operation avec son idFrais


### Transfert avec frais au choix

- [OK] Recevoir une demande de transfert du client
- [OK] Recuperer le choix du client
    - Transfert avec frais
    - Transfert sans frais

### Transfert avec frais

- [OK] Rechercher le frais correspondant
- [OK] Enregistrer l'operation avec idFrais


### Transfert sans frais

- [OK] Ne pas appliquer de frais
- [OK] Enregistrer l'operation avec idFrais = NULL


---

## Tache 3 : Situation des gains

- [OK] Verifier le type d'operation apres chaque operation terminee
- [OK] Appliquer le calcul du gain uniquement pour :
    - Retrait
    - Transfert
- [OK] Recuperer le frais applique a l'operation
- [OK] Creer un historique dans la table historiqueGain
- [OK] Enregistrer le gain de l'operateur dans la table gain
- [OK] Ignorer les operations de depot


---

## Tache 4 : Situation du compte client et notification

### Verification du numero client

- [OK] Rechercher le client dans la table client
- [OK] Verifier l'existence du numero
- [OK] Refuser l'operation si le numero est inconnu
- [OK] Retourner une notification :
    - Numero inconnu


### Verification du solde

- [OK] Recuperer le solde actuel du client
- [OK] Comparer le solde avec :
    - Montant operation
    - Frais eventuels
- [OK] Refuser l'operation si le solde est insuffisant
- [OK] Retourner une notification :
    - Solde insuffisant


### Validation finale

- [OK] Autoriser l'operation si toutes les conditions sont valides
- [OK] Mettre a jour le solde du client
- [OK] Enregistrer l'operation dans la table operation
- [OK] Ajouter l'historique de l'operation


---

# Front-end cote operateur

## Profil de l'operateur

- [OK] Afficher les informations de l'operateur
- [OK] Afficher les prefixes configures


## Liste des operations effectuees

- [OK] Afficher toutes les operations realisees par les clients
- [OK] Afficher :
    - Client
    - Type d'operation
    - Montant
    - Frais
    - Date


## Consultation des frais et tranches

- [OK] Afficher la configuration des frais
- [OK] Afficher les tranches :
    - Type operation
    - Minimum
    - Maximum
    - Valeur frais


## Situation des gains et historique

- [OK] Afficher les gains de l'operateur
- [OK] Afficher l'historique des gains


---

# Fichiers cote operateur

## Model

- [OK] operateur.php
- [OK] typeOperation.php
- [OK] operation.php
- [OK] gain.php
- [OK] historiqueGain.php
- [OK] frais.php


## Controller

- [OK] operateurControlleur.php


## View

- [OK] operateur/
    - [OK] profil.php
    - [OK] operations.php
    - [OK] frais.php
    - [OK] gains.php


---

# Cote client

## Tache 1 : Login

- [OK] Connexion avec numero telephone
- [OK] Verification du mot de passe


## Tache 2 : Operations

- [OK] Liste des operations disponibles
- [OK] Effectuer un depot
- [OK] Effectuer un retrait
- [OK] Effectuer un transfert
    - [OK] Choix avec frais
    - [OK] Choix sans frais


## Tache 3 : Consultation solde

- [OK] Afficher le solde actuel du client


## Tache 4 : Historique

- [OK] Afficher l'historique des operations du client


---

# Fichiers cote client

## Model

- [OK] client.php
- [OK] historique.php


## Controller

- [OK] clientControlleur.php


## View

- [OK] login/
- [OK] clients/
    - [OK] operations.php
    - [OK] voirSolde.php
    - [OK] historique.php