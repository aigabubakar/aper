<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdmins extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'fullname' => ['type'=>'VARCHAR','constraint'=>255],
            'email' => ['type'=>'VARCHAR','constraint'=>255],
            'password' => ['type'=>'VARCHAR','constraint'=>255],
            'role' => ['type'=>'VARCHAR','constraint'=>50,'default'=>'admin'],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('admins', true);
    }

    public function down()
    {
        $this->forge->dropTable('admins');
    }
}
