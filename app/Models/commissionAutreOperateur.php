<?php

namespace App\Models;

use CodeIgniter\Model;

class commissionAutreOperateur extends Model
{
    protected $table = 'commissionAutreOperateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['idOperateur', 'idTypeOperation', 'pourcentage'];
    protected $useTimestamps = false;

    public function pourcentagePour(int $operateurId, int $typeOperationId): float
    {
        $commission = $this->where('idOperateur', $operateurId)
            ->where('idTypeOperation', $typeOperationId)
            ->first();

        return (float) ($commission['pourcentage'] ?? 0);
    }
}
