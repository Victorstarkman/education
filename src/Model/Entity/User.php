<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Table\UsersTable;
use Authentication\PasswordHasher\DefaultPasswordHasher; // Add this line
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property string $license
 * @property string $phone
 * @property string $document
 * @property string $area
 * @property int $group_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $deleted
 *
 * @property \App\Model\Entity\Report[] $reports
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'name' => true,
        'lastname' => true,
        'email' => true,
        'password' => true,
        'license' => true,
        'licenseNational' => true,
        'phone' => true,
        'document' => true,
        'area' => true,
        'group_id' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'reports' => true,
        'city_id' => true,
        'signature' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }

    public function groupName()
    {
        return UsersTable::GROUPS[$this->group_id]['name'];
    }
}
