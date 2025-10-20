<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'staff_number' => ['type'=>'VARCHAR','constraint'=>50,'null'=>true],
            'fullname'     => ['type'=>'VARCHAR','constraint'=>191],
            'email'        => ['type'=>'VARCHAR','constraint'=>191],
            'department'   => ['type'=>'VARCHAR','constraint'=>191,'null'=>true],
            'created_at'   => ['type'=>'DATETIME','null'=>true],
            'updated_at'   => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->createTable('staffs');
    }

    public function down()
    {
        $this->forge->dropTable('staffs');
    }
}
