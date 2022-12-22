<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Job Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $message
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Job extends Entity
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
        'user_id' => true,
        'name' => true,
        'message' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];

	function getName() {
		$name = 'Desconocido';
		switch ($this->name) {
			case 'scrapperInit':
				$name = 'Scrappeando Sitio.';
				break;
			case 'scrapperProcessor':
				$name = 'Guardando datos en base de datos.';
				break;
		}

		return $name;
	}

	function getStatus() {
		$name = 'Desconocido';
		switch ($this->status) {
			case 1:
				$name = 'Corriendo';
				break;
			case 2:
				$name = 'Finalizado';
				break;
			case 3:
				$name = 'Error';
				break;
			case 4:
				$name = 'Completo';
				break;
		}

		return $name;
	}

	function getPorcentaje() {
		$porcentaje = 'Desconocido';
		if (!empty($this->message)) {
			$msg = json_decode($this->message, true);
			switch ($this->name) {
				case 'scrapperInit':
				case 'scrapperProcessor':
					if ($msg['processedPage'] > 0 && $msg['totalPages'] > 0) {
						$porcentaje = number_format(($msg['processedPage']*100)/$msg['totalPages'], 2) . '%';
					}
					break;
			}
		}

		return $porcentaje;
	}
}
