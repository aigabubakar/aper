<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['faculty_id' => 1, 'name' => 'Computer Science', 'code' => 'CSC', 'created_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 1, 'name' => 'Microbiology', 'code' => 'MIC', 'created_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 2, 'name' => 'Electrical Engineering', 'code' => 'EEE', 'created_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 2, 'name' => 'Civil Engineering', 'code' => 'Civ', 'created_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 3, 'name' => 'Accounting', 'code' => 'ACC', 'created_at' => date('Y-m-d H:i:s')],
            ['faculty_id' => 3, 'name' => 'Banking and Finance', 'code' => 'BNK', 'created_at' => date('Y-m-d H:i:s')],
        ];

        // Insert data
        $this->db->table('department')->insertBatch($departments);
    }
}
