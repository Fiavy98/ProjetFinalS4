<?php

namespace App\Models;

use CodeIgniter\Model;

class promotion extends Model
{
    protected $table = 'promotion';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'idOperateur',
        'valeur'
    ];

    protected $useTimestamps = false;

    public function getval(int $operateurId): float
    {
        $result = $this->value('valeur')->where('idOperateur', $operateurId)->first();
        return (float) ($result?? 0);
    }
}