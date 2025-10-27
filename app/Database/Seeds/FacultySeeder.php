<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run()
    {
        $faculties = [
            ['name' => 'Faculty of Science', 'code' => 'SCI', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Faculty of Engineering', 'code' => 'ENG', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Faculty of Management Sciences', 'code' => 'FMS', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Faculty of Arts', 'code' => 'ART', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Faculty of Education', 'code' => 'EDU', 'created_at' => date('Y-m-d H:i:s')],
        ];

        // Insert data
        $this->db->table('faculty')->insertBatch($faculties);
    }
}
