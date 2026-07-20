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
        'description',
        'dateheure'
    ];

    protected $useTimestamps = false;


    public function listeOperations()
    {
        return $this->select(
            'operation.*,
            client.nom as client,
            client.num,
            typeOperation.libele,
            frais.valeur as frais'
        )
        ->join('client','client.id = operation.idClient')
        ->join('typeOperation','typeOperation.id = operation.idTypeOperation')
        ->join('frais','frais.id = operation.idFrais','left')
        ->orderBy('operation.dateheure','DESC')
        ->findAll();
    }
}