<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'fullname' => 'Aigboje Abubakar',
            'email'    => 'abubakaraigboje@gmail.com',
            'password' => password_hash('P@ssw0rd', PASSWORD_DEFAULT),
            'role'     => 'superadmin',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->table('admins')->insert($data);
    }
}
