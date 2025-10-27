<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenusTable extends Migration
{
    public function up()
    {
        // table fields
        $this->forge->addField([
            'id'          => ['type'=>'INT', 'constraint'=>11, 'unsigned'=>true, 'auto_increment'=>true],
            'parent_id'   => ['type'=>'INT', 'constraint'=>11, 'unsigned'=>true, 'null'=>true, 'default'=>null],
            'label'       => ['type'=>'VARCHAR', 'constraint'=>191],
            'url'         => ['type'=>'VARCHAR', 'constraint'=>255, 'null'=>true],
            'icon'        => ['type'=>'VARCHAR', 'constraint'=>100, 'null'=>true],
            'roles'       => ['type'=>'TEXT', 'null'=>true, 'comment'=>'JSON array of roles allowed, null = all'],
            'categories'  => ['type'=>'TEXT', 'null'=>true, 'comment'=>'JSON array of categories allowed, null = all'],
            'order'       => ['type'=>'INT', 'constraint'=>5, 'default'=>0],
            'is_active'   => ['type'=>'TINYINT', 'constraint'=>1, 'default'=>1],
            'created_at'  => ['type'=>'DATETIME', 'null'=>true],
            'updated_at'  => ['type'=>'DATETIME', 'null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('parent_id');
        $this->forge->createTable('menus', true);
    }

    public function down()
    {
        $this->forge->dropTable('menus', true);
    }
}
