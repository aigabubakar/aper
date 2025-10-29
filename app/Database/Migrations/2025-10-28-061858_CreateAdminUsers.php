<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'username'    => ['type'=>'VARCHAR','constraint'=>120,'null'=>false],
            'fullname'    => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'email'       => ['type'=>'VARCHAR','constraint'=>255,'null'=>false],
            'password'    => ['type'=>'VARCHAR','constraint'=>255,'null'=>false],
            'role'        => ['type'=>'VARCHAR','constraint'=>50,'default'=>'admin'], // admin, superadmin etc
            'is_active'   => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'created_at'  => ['type'=>'DATETIME','null'=>true],
            'updated_at'  => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->createTable('admin_users', true);
    }

    public function down()
    {
        $this->forge->dropTable('admin_users', true);
    }
}
