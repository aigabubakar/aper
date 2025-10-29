<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('admin_users');

        $exists = $builder->where('email','abubakaraigboje@gmail.com')->get()->getRowArray();
        if ($exists) {
            echo "Admin already exists, skipping.\n";
            return;
        }

        $builder->insert([
            'username' => 'superadmin',
            'fullname' => 'Aigboje Abubakar',
            'email'    => 'abubakaraigboje@gmail.com',
            'password' => password_hash('Admin@1234', PASSWORD_DEFAULT),
            'role'     => 'superadmin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        echo "Created admin: abubakaraigboje@gmail.com / Admin@1234\n";
    }
}
