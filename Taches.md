# Version 1

# TODO LISTE

## [OK] Base de donnees

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


# Version 2

## Côté client 

- Frais
 - [OK] Option inclure frais de retrait lors de l’envoi
 - [OK] Affiché les frais à
 - [OK] Notifié le client d'un transfert réussit

- Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
même opérateur uniquement
 - [OK] ajout d'un nouveau formulaire multiple côté view client
 - [OK] script js pour calcul dynamique au côté front-end 
 - ClientController.php 
  - [OK] modifier le controller pour envoyer à plusieurs autres clients en même temps 


  ## Tache 1 : Configuration des prefixes valable pour les autre operateurs 
- ajoute les autre operateur dans base de donnes (Table operateurs)

## Tache 2 : Configuration % en plus de commissions pour les transferts vers les autres opérateurs  
### Base de donnees

- [ok] Ajouter une table commissionAutreOperateur
    - id
    - idOperateur
    - idTypeOperation
    - pourcentage

- [ok] Configurer le pourcentage de commission pour chaque operateur et type d operation

---

### Verification du transfert

- [ok] Recuperer le numero du destinataire
- [ok] Determiner son operateur a partir du prefixe
- [ok] Comparer l operateur de l expediteur et celui du destinataire

#### Meme operateur

- [ok] Appliquer uniquement le frais normal du transfert
- [ok] Continuer le traitement classique

#### Autre operateur

- [ok] Recuperer le pourcentage de commission correspondant
- [ok] Calculer la commission supplementaire
- [ok] Conserver le frais normal du transfert

---
### Calcul du montant a debiter

- [ok] Recuperer le montant du transfert
- [ok] Recuperer le frais normal selon la tranche
- [ok] Calculer la commission vers l autre operateur
- [ok] Calculer le montant total a debiter

*Total = montant + frais + commission*

## Tache 3 : Sur la page “Situation gain via les différents frais” , séparer opérateur et autres opérateurs

### Affichage des gains de l operateur

- [ok] Recuperer les gains provenant des frais des operations
- [ok] Afficher uniquement les gains appartenant a l operateur courant
- [ok] Afficher :
    - Date
    - Type d operation
    - Montant du frais
- [ok] Calculer le total des gains de l operateur

---

### Affichage des gains des autres operateurs

- [ok] Recuperer les commissions des transferts vers les autres operateurs
- [ok] Regrouper les gains par operateur
- [ok] Afficher :
    - Nom de l operateur
    - Date
    - Type d operation
    - Commission recue
- [ok] Calculer le total des commissions par operateur

---

### Situation generale

- [ok] Afficher deux sections distinctes
    - Gains de l operateur
    - Gains des autres operateurs
- [ok] Afficher le total de chaque section
- [ok] Permettre une consultation simple de l historique des gains

## Tache 4 : Situation des montants à envoyer à chaque opérateur
### Recuperation des transferts

- [ok] Recuperer tous les transferts vers les autres operateurs
- [ok] Identifier l operateur destinataire a partir du prefixe
- [ok] Ignorer les transferts effectues vers le meme operateur

---

### Calcul des montants

- [ok] Recuperer le montant de chaque transfert
- [ok] Regrouper les montants par operateur destinataire
- [ok] Calculer le total a envoyer pour chaque operateur

---

### Affichage de la situation

- [ok] Afficher le nom de chaque operateur
- [ok] Afficher le nombre de transferts
- [ok] Afficher le montant total a envoyer
- [ok] Afficher le total general des montants a envoyer

---

### Verification

- [ok] Verifier que seuls les transferts vers un autre operateur sont pris en compte
- [ok] Verifier que les montants correspondent aux operations enregistrees
- [ok] Verifier que les calculs sont corrects
---
Rédiger
