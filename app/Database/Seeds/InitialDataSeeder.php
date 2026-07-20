<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        if ($db->table('typeOperation')->countAllResults() === 0) {
            $db->table('typeOperation')->insertBatch([
                ['libele' => 'depot'],
                ['libele' => 'retrait'],
                ['libele' => 'transfert'],
            ]);
        }

        if ($db->table('frais')->countAllResults() === 0) {
            $type = $db->table('typeOperation')->where('libele', 'transfert')->get()->getFirstRow('array');
            if ($type) {
                $db->table('frais')->insert([
                    'idTypeOperation' => $type['id'],
                    'min' => 0,
                    'max' => 999999999,
                    'valeur' => 500,
                ]);
            }
        }

        if ($db->table('client')->countAllResults() === 0) {
            $db->table('client')->insertBatch([
                [
                    'num' => '770000001',
                    'mdp' => '1234',
                    'nom' => 'Client Demo 1',
                    'solde' => 100000,
                ],
                [
                    'num' => '770000002',
                    'mdp' => '1234',
                    'nom' => 'Client Demo 2',
                    'solde' => 50000,
                ],
            ]);
        }
    }
}