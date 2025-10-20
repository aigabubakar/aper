<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQualificationsToUsers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        if (! $db->tableExists('users')) {
            throw new \RuntimeException('users table not found. Create users first.');
        }

        $existing = $db->getFieldNames('users');

        $cols = [];

        // Academic qualifications (5)
        for ($i = 1; $i <= 5; $i++) {
            $k = "qual{$i}";
            if (! in_array($k, $existing)) {
                $cols[$k] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null];
            }
            $g = "qual{$i}_grade";
            if (! in_array($g, $existing)) {
                $cols[$g] = ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'default' => null];
            }
            $ins = "qual{$i}_institution";
            if (! in_array($ins, $existing)) {
                $cols[$ins] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null];
            }
            $d = "qual{$i}_date";
            if (! in_array($d, $existing)) {
                $cols[$d] = ['type' => 'DATE', 'null' => true, 'default' => null];
            }
        }

        // Professional qualifications (5)
        for ($i = 1; $i <= 5; $i++) {
            $pq = "prof_qual{$i}";
            if (! in_array($pq, $existing)) {
                $cols[$pq] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null];
            }
            $pb = "prof_qual{$i}_body";
            if (! in_array($pb, $existing)) {
                $cols[$pb] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null];
            }
            $pd = "prof_qual{$i}_date";
            if (! in_array($pd, $existing)) {
                $cols[$pd] = ['type' => 'DATE', 'null' => true, 'default' => null];
            }
        }

        if (! empty($cols)) {
            $this->forge->addColumn('users', $cols);
            log_message('info', 'AddQualificationsToUsers added columns: ' . implode(',', array_keys($cols)));
        } else {
            log_message('info', 'AddQualificationsToUsers: nothing to add');
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        if (! $db->tableExists('users')) return;

        $existing = $db->getFieldNames('users');
        $drop = [];

        for ($i = 1; $i <= 5; $i++) {
            foreach (["qual{$i}","qual{$i}_grade","qual{$i}_institution","qual{$i}_date"] as $c) {
                if (in_array($c, $existing)) $drop[] = $c;
            }
            foreach (["prof_qual{$i}","prof_qual{$i}_body","prof_qual{$i}_date"] as $c) {
                if (in_array($c, $existing)) $drop[] = $c;
            }
        }

        if (! empty($drop)) {
            $this->forge->dropColumn('users', $drop);
            log_message('info', 'AddQualificationsToUsers removed columns: ' . implode(',', $drop));
        }
    }
}
