<?php
declare(strict_types=1);

use Migrations\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;
/**
 * TblUsers seed.
 */
class TblUsersSeed extends AbstractSeed
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
        $hash= new DefaultPasswordHasher();
        $data = [
            'first_name' => 'Victor',
            'last_name' => 'Starkman',
            'email'=> 'victorstarkman@gmail.com',
            'password' => $hash->hash('admin@123'),
            'group_id'=>1,
        ];

        $table = $this->table('tbl_users');
        $table->insert($data)->save();
    }
}
