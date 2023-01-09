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
				$name = 'Procesando Datos.';
				break;
			case 'scrapperProcessor':
				$name = 'Guardando datos en base de datos.';
				break;
			case 'manualRequestScrap':
				$name = 'Procesamiento de datos pedido manualmente.';
				break;
		}

		return $name;
	}

	function getStatus() {
		$name = 'Desconocido';
		switch ($this->status) {
			case 1:
				$name = 'Procesando';
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

	function showProgressBar() {
		$show = [];
		if (!empty($this->message)) {
			$msg = json_decode($this->message, true);
			if ($this->status !=3) {
				switch ($this->name) {
					case 'scrapperInit':
						if ($msg['totalPages'] > 0) {
							$show[]= number_format((float) $msg['percentage'], 2);
						}
						break;
					case 'scrapperProcessor':
						if ($msg['totalPages'] > 0) {
							$show[] = number_format(($msg['processedPage']*100)/$msg['totalPages'], 2);
						}
						break;
				}
			}
		}

		return $show;
	}

	function progressBar() {
		$value = $this->showProgressBar();
		$percentage = 'Desconocido';
		if (!empty($value)) {
			$percentage = '';
			foreach ($value as $name => $per) {
				$class = ($per < 100) ? 'progress-bar-striped progress-bar-animated' : '';
				if (!is_int($name)) {
					$percentage .= '<span style="text-align: left!important; font-size: 10px;">' . $name . '</span>';
				}
				$percentage .= '<div class="progress" style="margin-bottom: 4px;"><div class="progress-bar bg-success ' . $class .'" role="progressbar" aria-valuenow="' . $per.'" aria-valuemin="0" aria-valuemax="100" style="width:' . $per . '%;color: black;font-weight: bold;">' . $per . '%</div></div>';
			}
		} elseif ($this->status == 3) {
			$percentage = '-';
		}

		return $percentage;
	}
}
