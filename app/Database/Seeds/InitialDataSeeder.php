<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        $baseSql = ROOTPATH . 'base.sql';
        if (! is_file($baseSql)) {
            throw new \RuntimeException('base.sql introuvable pour le seed initial.');
        }

        $db->transStart();

        $db->query('DELETE FROM historiqueOperationClient');
        $db->query('DELETE FROM gain');
        $db->query('DELETE FROM historiqueGain');
        $db->query('DELETE FROM operation');
        $db->query('DELETE FROM frais');
        $db->query('DELETE FROM commissionAutreOperateur');
        $db->query('DELETE FROM client');
        $db->query('DELETE FROM typeOperation');
        $db->query('DELETE FROM operateur');
        $db->query('DELETE FROM sqlite_sequence');

        $sql = file_get_contents($baseSql);
        $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
        $statements = preg_split('/;\s*(?:\R|$)/', $sql) ?: [];

        foreach ($statements as $statement) {
            $statement = trim($statement);

            if ($statement === '' || ! preg_match('/^INSERT\s+INTO\s+/i', $statement)) {
                continue;
            }

            $db->query($statement);
        }

        $db->transComplete();

    }
}
