<?php

namespace App\Models;

use CodeIgniter\Model;

class gain extends Model
{
    protected $table = 'gain';
    protected $primaryKey = 'id';
    protected $allowedFields = ['idOperateur', 'idHistorique', 'valeur'];
    protected $useTimestamps = false;

    public function historiqueGain(int $operateurId): array
    {
        return $this->select('gain.*, historiqueGain.dateheure, operation.description, typeOperation.libele')
            ->join('historiqueGain', 'historiqueGain.id = gain.idHistorique')
            ->join('operation', 'operation.id = historiqueGain.idOperation')
            ->join('typeOperation', 'typeOperation.id = operation.idTypeOperation')
            ->where('gain.idOperateur', $operateurId)
            ->orderBy('historiqueGain.dateheure', 'DESC')
            ->findAll();
    }

    public function totalGains(int $operateurId): float
    {
        $result = $this->selectSum('valeur', 'total')->where('idOperateur', $operateurId)->first();
        return (float) ($result['total'] ?? 0);
    }

    public function commissionsAutresOperateurs(int $operateurSourceId): array
    {
        return db_connect()->table('operation')
            ->select('operateur.nom AS operateur, operation.dateheure, typeOperation.libele, operation.commission')
            ->join('operateur', 'operateur.id = operation.idOperateurDestinataire')
            ->join('typeOperation', 'typeOperation.id = operation.idTypeOperation')
            ->where('operation.idOperateurSource', $operateurSourceId)
            ->where('operation.idOperateurDestinataire !=', $operateurSourceId)
            ->where('operation.idOperateurDestinataire IS NOT NULL', null, false)
            ->where('operation.commission >', 0)
            ->orderBy('operation.dateheure', 'DESC')
            ->get()->getResultArray();
    }

    public function totauxCommissionsAutresOperateurs(int $operateurSourceId): array
    {
        return db_connect()->table('operation')
            ->select('operateur.nom AS operateur, SUM(operation.commission) AS total_commission')
            ->join('operateur', 'operateur.id = operation.idOperateurDestinataire')
            ->where('operation.idOperateurSource', $operateurSourceId)
            ->where('operation.idOperateurDestinataire !=', $operateurSourceId)
            ->where('operation.idOperateurDestinataire IS NOT NULL', null, false)
            ->where('operation.commission >', 0)
            ->groupBy('operation.idOperateurDestinataire, operateur.nom')
            ->orderBy('operateur.nom', 'ASC')
            ->get()->getResultArray();
    }
}
