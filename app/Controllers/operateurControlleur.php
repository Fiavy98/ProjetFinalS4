<?php

namespace App\Controllers;

use App\Models\client as ClientModel;
use App\Models\frais as FraisModel;
use App\Models\gain as GainModel;
use App\Models\operateur as OperateurModel;
use App\Models\operation as OperationModel;
use App\Models\typeOperation as TypeOperationModel;

class operateurControlleur extends BaseController
{
    private const ADMIN_NUM = '999999999';
    private const ADMIN_PASSWORD = 'admin123';

    public function login()
    {
        helper(['form', 'url']);

        if ($this->request->is('post')) {
            $numero = trim((string) $this->request->getPost('num'));
            $mdp = trim((string) $this->request->getPost('mdp'));

            if ($numero === self::ADMIN_NUM && $mdp === self::ADMIN_PASSWORD) {
                session()->set('operateur_logged_in', true);
                return redirect()->to('/operateur');
            }

            return redirect()->to('/')->with('admin_error', 'Numero ou mot de passe administrateur invalide.');
        }

        return redirect()->to('/');
    }

    public function logout()
    {
        session()->remove('operateur_logged_in');
        return redirect()->to('/')->with('admin_message', 'Deconnexion operateur effectuee.');
    }

    public function verifierPrefixe($numero)
    {
        $operateurModel = new OperateurModel();

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
        $fraisModel = new FraisModel();

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
        $clientModel = new ClientModel();
    
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
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/')->with('admin_message', 'Connectez-vous pour acceder a l espace operateur.');
        }

        return view('operateur/index');
    }

    // Profil opérateur

    public function profil()
    {
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $model = new OperateurModel();


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
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $model = new OperationModel();


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
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $model = new FraisModel();


        $data=[
            'frais'=>$model->listeFrais(),
            'typesOperation'=>(new TypeOperationModel())->findAll(),
        ];


        return view(
            'operateur/frais',
            $data
        );

    }

    public function creerFrais()
    {
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $donnees = $this->donneesFrais();
        if ($donnees === null) {
            return redirect()->to('/operateur/frais')->with('frais_error', 'Veuillez renseigner des montants valides : le minimum ne peut pas dépasser le maximum.');
        }

        (new FraisModel())->insert($donnees);
        return redirect()->to('/operateur/frais')->with('frais_success', 'La tranche de frais a été ajoutée.');
    }

    public function modifierFrais($id)
    {
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $frais = (new FraisModel())->find($id);
        if (! $frais) {
            return redirect()->to('/operateur/frais')->with('frais_error', 'Cette tranche de frais est introuvable.');
        }

        return view('operateur/frais_formulaire', [
            'frais' => $frais,
            'typesOperation' => (new TypeOperationModel())->findAll(),
        ]);
    }

    public function mettreAJourFrais($id)
    {
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $model = new FraisModel();
        if (! $model->find($id)) {
            return redirect()->to('/operateur/frais')->with('frais_error', 'Cette tranche de frais est introuvable.');
        }

        $donnees = $this->donneesFrais();
        if ($donnees === null) {
            return redirect()->to('/operateur/frais/' . $id . '/modifier')->with('frais_error', 'Veuillez renseigner des montants valides : le minimum ne peut pas dépasser le maximum.');
        }

        $model->update($id, $donnees);
        return redirect()->to('/operateur/frais')->with('frais_success', 'La tranche de frais a été modifiée.');
    }

    public function supprimerFrais($id)
    {
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $model = new FraisModel();
        if (! $model->find($id)) {
            return redirect()->to('/operateur/frais')->with('frais_error', 'Cette tranche de frais est introuvable.');
        }

        if ((new OperationModel())->where('idFrais', $id)->first()) {
            return redirect()->to('/operateur/frais')->with('frais_error', 'Cette tranche est déjà utilisée par une opération et ne peut pas être supprimée.');
        }

        $model->delete($id);
        return redirect()->to('/operateur/frais')->with('frais_success', 'La tranche de frais a été supprimée.');
    }

    private function donneesFrais(): ?array
    {
        $type = filter_var($this->request->getPost('idTypeOperation'), FILTER_VALIDATE_INT);
        $min = filter_var($this->request->getPost('min'), FILTER_VALIDATE_FLOAT);
        $max = filter_var($this->request->getPost('max'), FILTER_VALIDATE_FLOAT);
        $valeur = filter_var($this->request->getPost('valeur'), FILTER_VALIDATE_FLOAT);

        if ($type === false || $type < 1 || $min === false || $max === false || $valeur === false || $min < 0 || $max < $min || $valeur < 0) {
            return null;
        }

        if (! (new TypeOperationModel())->find($type)) {
            return null;
        }

        return ['idTypeOperation' => $type, 'min' => $min, 'max' => $max, 'valeur' => $valeur];
    }

    // Gain
    public function gains()
    {
        if (! session()->get('operateur_logged_in')) {
            return redirect()->to('/');
        }

        $model = new GainModel();


        $data=[
            'gains'=>$model->historiqueGain(),
            'totalGains'=>$model->totalGains(),
        ];


        return view(
            'operateur/gains',
            $data
        );

    }


}
