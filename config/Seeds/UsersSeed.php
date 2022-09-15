<?php
declare(strict_types=1);

use Cake\Auth\DefaultPasswordHasher;
use Migrations\AbstractSeed;

/**
 * TblUsers seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $hash = new DefaultPasswordHasher();

        $data = [
            [
                'name' => 'Usuario',
                'lastname' => 'Admin',
                'email' => 'aloisejulian+admin@gmail.com',
                'password' => $hash->hash('admin@123'),
                'group_id' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'deleted' => null,
            ],
            [
                'name' => 'Usuario',
                'lastname' => 'Auditor',
                'email' => 'aloisejulian+auditor@gmail.com',
                'password' => $hash->hash('auditor@123'),
                'group_id' => 2,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'deleted' => null,
                'license' => '1234',
                'licenseNational' => '5678',
                'phone' => 1155556666,
                'document' => 12345345,
                'area' => 'medica',
            ],
            [
                'name' => 'Usuario',
                'lastname' => 'Red',
                'email' => 'aloisejulian+red@gmail.com',
                'password' => $hash->hash('red@123'),
                'group_id' => 3,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'deleted' => null,
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
