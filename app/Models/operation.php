<?php

namespace App\Models;

use CodeIgniter\Model;

class operation extends Model
{
    protected $table = 'operation';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'idTypeOperation',
        'idClient',
        'valeur',
        'idFrais',
        'idOperateurSource',
        'idOperateurDestinataire',
        'commission',
        'description',
        'dateheure'
    ];

    protected $useTimestamps = false;


    public function listeOperations(?int $operateurSourceId = null): array
    {
        $query = $this->select(
            'operation.*,
            client.nom as client,
            client.num,
            typeOperation.libele,
            frais.valeur as frais,
            operation.commission,
            destinataire.nom as operateur_destinataire'
        )
        ->join('client','client.id = operation.idClient')
        ->join('typeOperation','typeOperation.id = operation.idTypeOperation')
        ->join('frais','frais.id = operation.idFrais','left')
        ->join('operateur destinataire', 'destinataire.id = operation.idOperateurDestinataire', 'left')
        ->orderBy('operation.dateheure','DESC');

        if ($operateurSourceId !== null) {
            $query->where('operation.idOperateurSource', $operateurSourceId);
        }

        return $query->findAll();
    }

    public function montantsExternesParOperateur(int $operateurSourceId): array
    {
        return $this->select('operateur.nom, COUNT(operation.id) AS nombre_transferts, SUM(operation.valeur) AS montant_total')
            ->join('operateur', 'operateur.id = operation.idOperateurDestinataire')
            ->join('typeOperation', 'typeOperation.id = operation.idTypeOperation')
            ->where('operation.idOperateurSource', $operateurSourceId)
            ->where('operation.idOperateurDestinataire !=', $operateurSourceId)
            ->where('operation.idOperateurDestinataire IS NOT NULL', null, false)
            ->where("LOWER(typeOperation.libele) = 'transfert'", null, false)
            ->groupBy('operation.idOperateurDestinataire, operateur.nom')
            ->orderBy('operateur.nom', 'ASC')
            ->findAll();
    }
}
