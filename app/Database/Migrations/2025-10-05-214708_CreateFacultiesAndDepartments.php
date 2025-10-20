<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFacultiesAndDepartments extends Migration
{
    public function up()
    {
        // Faculties
        if (! $this->db->tableExists('faculties')) {
            $this->forge->addField([
                'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
                'name' => ['type'=>'VARCHAR','constraint'=>150],
                'code' => ['type'=>'VARCHAR','constraint'=>50,'null'=>true],
                'created_at' => ['type'=>'DATETIME','null'=>true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('faculties', true);
        }

        // Departments
        if (! $this->db->tableExists('departments')) {
            $this->forge->addField([
                'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
                'faculty_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true],
                'name' => ['type'=>'VARCHAR','constraint'=>150],
                'code' => ['type'=>'VARCHAR','constraint'=>50,'null'=>true],
                'created_at' => ['type'=>'DATETIME','null'=>true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('faculty_id');
            // add FK if faculties exist
            if ($this->db->tableExists('faculties')) {
                $this->forge->addForeignKey('faculty_id','faculties','id','SET NULL','CASCADE');
            }
            $this->forge->createTable('departments', true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('departments', true);
        $this->forge->dropTable('faculties', true);
    }
}
