<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Table\CompaniesTable;
use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property string $razon
 * @property string|null $name
 * @property string $cuit
 * @property int $no_dienst
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $deleted
 */
class Company extends Entity
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
        'razon' => true,
        'name' => true,
        'cuit' => true,
        'no_dienst' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];

    public function getNameStatus()
    {
        return CompaniesTable::STATUSES[$this->status]['name'];
    }

    public function isDienst()
    {
        return $this->no_dienst == 1 ? 'Si' : 'No';
    }
}
