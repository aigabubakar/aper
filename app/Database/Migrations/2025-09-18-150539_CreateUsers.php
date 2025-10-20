<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // If the users table already exists, do nothing
        if ($db->tableExists('users')) {
            return;
        }

        $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'staff_id' => ['type'=>'INT','constraint'=>11,'null'=>true],
            'fullname' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'email' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'password' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'role' => ['type'=>'VARCHAR','constraint'=>50,'null'=>true],
            'verify_token' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'email_verified_at' => ['type'=>'DATETIME','null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
            'deleted_at' => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
