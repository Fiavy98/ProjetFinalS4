<?php

namespace App\Controllers;

use App\Models\client;
use App\Models\frais;
use App\Models\gain;
use App\Models\operateur;
use App\Models\operation;

class operateurControlleur extends BaseController
{
    public function verifierPrefixe($numero)
    {
        $operateurModel = new operateur();

        // Récupérer les opérateurs
        $operateurs = $operateurModel->findAll();

        foreach ($operateurs as $operateur) {

            // Les préfixes sont stockés par exemple : "033,037"
            $prefixes = explode(',', $operateur['prefixes']);

            foreach ($prefixes as $prefixe) {

                // Vérifie si le numéro commence par ce préfixe
                if (str_starts_with($numero, trim($prefixe))) {
                    return [
                        'valide' => true,
                        'operateur' => $operateur['nom']
                    ];
                }
            }
        }

        return [
            'valide' => false,
            'message' => 'Numéro invalide : préfixe non reconnu'
        ];
    }

    public function calculerFrais($idTypeOperation, $montant, $avecFrais = true)
    {
        $fraisModel = new frais();

        // Transfert sans frais
        if ($idTypeOperation == 3 && !$avecFrais) {
            return null;
        }

        $frais = $fraisModel
            ->where('idTypeOperation', $idTypeOperation)
            ->where('min <=', $montant)
            ->where('max >=', $montant)
            ->first();

        return $frais;
    }

    public function verifierCompte($idClient, $montant)
    {
        $clientModel = new client();
    
        $client = $clientModel->find($idClient);
    
    
        if(!$client){
            return [
                'success'=>false,
                'message'=>'Client inconnu'
            ];
        }
    
    
        if($client['solde'] < $montant){
            return [
                'success'=>false,
                'message'=>'Solde insuffisant'
            ];
        }
    
    
        return [
            'success'=>true
        ];
    }
    public function index()
    {
        return view('operateur/index');
    }

    // Profil opérateur

    public function profil()
    {

        $model = new operateur();


        $data=[
            'operateur'=>$model->first()
        ];


        return view(
            'operateur/profil',
            $data
        );

    }

    // Toutes les opérations
    public function operations()
    {

        $model=new operation();


        $data=[
            'operations'=>$model->listeOperations()
        ];


        return view(
            'operateur/operations',
            $data
        );

    }





    // Affichage frais

    public function frais()
    {

        $model = new frais();


        $data=[
            'frais'=>$model->listeFrais()
        ];


        return view(
            'operateur/frais',
            $data
        );

    }

    // Gain
    public function gains()
    {

        $model = new gain();


        $data=[
            'gains'=>$model->historiqueGain()
        ];


        return view(
            'operateur/gains',
            $data
        );

    }


}
