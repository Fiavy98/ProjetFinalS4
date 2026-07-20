<?php

namespace App\Controllers;

class operateurControlleur extends BaseController
{
    public function verifierPrefixe($numero)
    {
        $operateurModel = new \App\Models\operateur();

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
        $fraisModel = new \App\Models\frais();
    
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


}
