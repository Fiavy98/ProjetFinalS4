<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVersionTwoTransferData extends Migration
{
    public function up()
    {
        $forge = \Config\Database::forge();

        if (! $this->db->fieldExists('idOperateurSource', 'operation')) {
            $forge->addColumn('operation', [
                'idOperateurSource' => ['type' => 'INTEGER', 'null' => true, 'after' => 'idFrais'],
                'idOperateurDestinataire' => ['type' => 'INTEGER', 'null' => true, 'after' => 'idOperateurSource'],
                'commission' => ['type' => 'REAL', 'default' => 0, 'null' => false, 'after' => 'idOperateurDestinataire'],
            ]);
        }

        $this->db->table('operateur')->where('nom', 'MVola')->update(['nom' => 'YAS']);
        $this->db->query("UPDATE operation SET idOperateurSource = 1 WHERE idOperateurSource IS NULL AND EXISTS (SELECT 1 FROM client WHERE client.id = operation.idClient AND (client.num LIKE '034%' OR client.num LIKE '038%'))");
        $this->db->table('gain')->update(['idOperateur' => 1]);
        $this->db->query('UPDATE frais SET max = 10000.99 WHERE max = 10000');
        $this->db->query('UPDATE frais SET max = 50000.99 WHERE max = 50000');

        if ($this->db->table('commissionAutreOperateur')->countAllResults() === 0) {
            $this->db->table('commissionAutreOperateur')->insertBatch([
                ['idOperateur' => 1, 'idTypeOperation' => 3, 'pourcentage' => 0],
                ['idOperateur' => 2, 'idTypeOperation' => 3, 'pourcentage' => 5],
                ['idOperateur' => 3, 'idTypeOperation' => 3, 'pourcentage' => 5],
            ]);
        }
    }

    public function down()
    {
        // SQLite cannot safely remove columns without rebuilding the table.
    }
}
