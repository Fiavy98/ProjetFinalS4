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
    - ex : 034,038 devient 034 et 038
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

# Cote operateur 

## Tache 1 : Configuration des prefixes valable pour les autre operateurs 
- ajoute les autre operateur dans base de donnes (Table operateurs)

## Tache 2 : Configuration % en plus de commissions pour les transferts vers les autres opérateurs  
### Base de donnees

- [ ] Ajouter une table `commissionAutreOperateur`
    - id
    - idOperateur
    - idTypeOperation
    - pourcentage

- [ ] Configurer le pourcentage de commission pour chaque operateur et type d operation

---

### Verification du transfert

- [ ] Recuperer le numero du destinataire
- [ ] Determiner son operateur a partir du prefixe
- [ ] Comparer l operateur de l expediteur et celui du destinataire

#### Meme operateur

- [ ] Appliquer uniquement le frais normal du transfert
- [ ] Continuer le traitement classique

#### Autre operateur

- [ ] Recuperer le pourcentage de commission correspondant
- [ ] Calculer la commission supplementaire
- [ ] Conserver le frais normal du transfert

---
### Calcul du montant a debiter

- [ ] Recuperer le montant du transfert
- [ ] Recuperer le frais normal selon la tranche
- [ ] Calculer la commission vers l autre operateur
- [ ] Calculer le montant total a debiter

**Total = montant + frais + commission**

## Tache 3 : Sur la page “Situation gain via les différents frais” , séparer opérateur et autres opérateurs

### Affichage des gains de l operateur

- [ ] Recuperer les gains provenant des frais des operations
- [ ] Afficher uniquement les gains appartenant a l operateur courant
- [ ] Afficher :
    - Date
    - Type d operation
    - Montant du frais
- [ ] Calculer le total des gains de l operateur

---

### Affichage des gains des autres operateurs

- [ ] Recuperer les commissions des transferts vers les autres operateurs
- [ ] Regrouper les gains par operateur
- [ ] Afficher :
    - Nom de l operateur
    - Date
    - Type d operation
    - Commission recue
- [ ] Calculer le total des commissions par operateur

---

### Situation generale

- [ ] Afficher deux sections distinctes
    - Gains de l operateur
    - Gains des autres operateurs
- [ ] Afficher le total de chaque section
- [ ] Permettre une consultation simple de l historique des gains

## Tache 4 : Situation des montants à envoyer à chaque opérateur
### Recuperation des transferts

- [ ] Recuperer tous les transferts vers les autres operateurs
- [ ] Identifier l operateur destinataire a partir du prefixe
- [ ] Ignorer les transferts effectues vers le meme operateur

---

### Calcul des montants

- [ ] Recuperer le montant de chaque transfert
- [ ] Regrouper les montants par operateur destinataire
- [ ] Calculer le total a envoyer pour chaque operateur

---

### Affichage de la situation

- [ ] Afficher le nom de chaque operateur
- [ ] Afficher le nombre de transferts
- [ ] Afficher le montant total a envoyer
- [ ] Afficher le total general des montants a envoyer

---

### Verification

- [ ] Verifier que seuls les transferts vers un autre operateur sont pris en compte
- [ ] Verifier que les montants correspondent aux operations enregistrees
- [ ] Verifier que les calculs sont corrects
---

## Fichier 

### A modifier
- operateur.php
- operation.php
- gain.php
- historiqueGain.php

### A creer

- commissionAutreOperateur.php

## Views

### A modifier

- operateur/
    - index.php
    - gains.php

### A creer

- operateur/
    - commissionAutreOperateur.php
    - montantOperateur.php

## route 
- operateur/commission
- operateur/montantOperateur

## Fonctionnalites a ajouter dans operateurControlleur

### Configuration

- configurationCommission()

### Verification

- verifierOperateurDestinataire()

### Calcul

- calculerCommissionAutreOperateur()

### Gain

- gainOperateur()

- gainAutreOperateur()

### Situation

- situationGain()

- situationMontantOperateur()

### Affichage

- afficherCommission()

- afficherMontantOperateur()


# Cote client 
## Tache 1 : Option inclure frais de retrait lors de l’envoi
## Tache 2 : Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
