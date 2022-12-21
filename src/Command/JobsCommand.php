<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\I18n\FrozenTime;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\MessengerComponent;

/**
 * Appointment command.
 */
class JobsCommand extends Command
{

	private function consoleLog($text = '') {
		$this->logs[] = $text;
		$this->io->out($text);
	//	$this->file->append($text."\r\n");
	}

	public function initialize(): void
	{
		parent::initialize();
		$this->io = new ConsoleIo();
	//	$this->file = new File(ROOT . DS . 'logs/import_police_appointment.log');
	//	$this->Messenger = new MessengerComponent(new ComponentRegistry(), []);

		$this->statuses = [
			'running' => [0,1],
			'finished' => 2,
			'error' => 3,
			'completed' => 4,
		];
	}

	protected $defaultTable = "Jobs";

	public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
	{
		$parser = parent::buildOptionParser($parser);
		$parser
			->setDescription('Command to run cron jobs')
			->addOption('type', [
				'help' => 'Choose cron type',
				'default' => 'scrapperProcessor',
			]);

		return $parser;
	}

	/**
	 * Implement this method with your command's logic.
	 *
	 * @param \Cake\Console\Arguments $args The command arguments.
	 * @param \Cake\Console\ConsoleIo $io The console io
	 * @return null|void|int The exit code or null for success
	 */
	public function execute(Arguments $args, ConsoleIo $io)
	{
		$type = $args->getOption('type');
		$this->consoleLog('Starting Job type: ' . $type);
		if ($this->jobRunning($type)) {
			$this->io->abort('Other job same type is running. Aborting command.');
		}

		switch ($type) {
			case 'scrapperInit':
				$this->initCommand();
				break;
			case 'scrapperProcessor':
				$this->processorCommand();
				break;
			case 'checkInitCommand':
				$this->checkInitCommand();
				break;
			default:
				$this->io->abort('Type unknown. Aborting command.');
				break;
		}
	}

	private function initCommand() {
		$this->saveOnTable('jobs', [
			'name' => 'scrapperInit',
			'status' => 1,
			'user_id' => 1,
		]);

		exec("cd ..;php Service/Bot.php");
	}

	private function checkInitCommand() {
		if ($lastId = $this->jobFinished('scrapperInit')) {
			$jobsTable = $this->fetchTable('Jobs');
			$job = $jobsTable->get($lastId);
			$job->status = $this->statuses['completed'];
			$jobsTable->save($job);
			$this->saveOnTable('jobs', [
				'name' => 'scrapperProcessor',
				'status' => 1,
				'user_id' => 1,
			]);
			$this->executeCommand(JobsCommand::class);
		}
	}

	private function processorCommand() {
		if ($this->jobRunning('scrapperInit')) {
			$this->io->abort('ScrapperInit running, processor should run when this process finish. Aborting command.');
		}

		$today = new FrozenTime();
		$directoryFiles = ROOT . DS . 'Service' . DS . 'File' . DS . 'Users' . DS . $today->format('Y-m-d');

		if (!file_exists($directoryFiles)) {
			$this->io->abort('Directory not found. Aborting command. Directory path: ' . $directoryFiles);
		}

		$dir = new Folder($directoryFiles);
		$files = $dir->read(true, ['files']);
		// Recorro las paginas
		$this->consoleLog('Reading directories.');
		$patientsTable = $this->fetchTable('Patients');
		foreach ($files[0] as $file) {
			$dir2 = new Folder($directoryFiles . DS . $file);
			$files2 = $dir2->read(true);
			//Recorro los registros
			foreach ($files2[0] as $file2) {
				$dir3 = new Folder($directoryFiles . DS . $file . DS . $file2);

				$files3 = $dir3->read(true);
				foreach ($files3 as $file3) {
					if (!empty($file3)) {
						foreach ($file3 as $userFolder) {
							$this->consoleLog('Reading user file.');
							$userFile = json_decode(file_get_contents($directoryFiles . DS . $file . DS . $file2 . DS . $userFolder . DS . 'json/jsonOriginResponse.json'), true);
							if (!empty($userFile['solicitudLicencia'])) {
								if (!empty($userFile['solicitudLicencia']['agente'])) {
									$patientID = $this->searchOnTableOrCreate('Patients', [
										'document' => trim($userFile['solicitudLicencia']['agente']['cuil']),
									],
										[
											'name' => trim($userFile['solicitudLicencia']['agente']['apellidoNombre']),
											'email' => trim($userFile['solicitudLicencia']['agente']['emailAlternativo']),
											'official_email' => trim($userFile['solicitudLicencia']['agente']['email']),
											'document' => trim($userFile['solicitudLicencia']['agente']['cuil']),
											'phone' => $userFile['solicitudLicencia']['agente']['area'] . '' . $userFile['solicitudLicencia']['agente']['numCelular'],
											'company_id' => 1,
											'externalID' => $userFile['solicitudLicencia']['agente']['id'],
										]
									);

									if (!empty($userFile['diagnostico'])) {
										$medicalCenterID = null;
										if (!empty($userFile['solicitudLicencia']['agente']['centroAuditoria'])) {
											$this->consoleLog('Searching Medical Center.');
											$userMedicalCenterInfo = $userFile['solicitudLicencia']['agente']['centroAuditoria'];
											$medicalCenterID = $this->searchOnTableOrCreate(
												'MedicalCenters' ,
												['district' => trim($userMedicalCenterInfo['razonSocial'])],
												['district' => trim($userMedicalCenterInfo['razonSocial'])]);
										}

										$cie10ID = null;
										if (!empty($userFile['diagnostico']['idDiagnostico'])) {
											$cie10ID = $this->searchOnTableOrCreate(
												'Cie10' ,
												['code' => $userFile['diagnostico']['idDiagnostico']],
												[
													'code' => $userFile['diagnostico']['idDiagnostico'],
													'name' => trim($userFile['diagnostico']['detalle']),
												],
											);
										}

										$privateDoctorID = null;
										if (!empty($userFile['solicitudLicencia']['medicoParticular'])) {
											$doctorData = $userFile['solicitudLicencia']['medicoParticular'];
											$privateDoctorID = $this->searchOnTableOrCreate(
												'privatedoctors' ,
												[
													'OR' => [
														'license' => $doctorData['matriculaProvincial'],
														'licenseNational' => $doctorData['matriculaNacional'],
													]
												],
												[
													'name' => trim($doctorData['nombre']),
													'lastname' => trim($doctorData['apellido']),
													'license' => ($doctorData['matriculaProvincial'] > 0) ? $doctorData['matriculaProvincial'] : null,
													'licenseNational' => ($doctorData['matriculaNacional'] > 0) ? $doctorData['matriculaNacional'] : null,
												],
											);

											$specialityID = $this->searchOnTableOrCreate('specialties',
												['name' => $doctorData['especialidad']['especialidad']],
												['name' => $doctorData['especialidad']['especialidad']]);
										}


										$reportTable = $this->fetchTable('Reports');
										$status = $reportTable::ACTIVE;
										$modeID = $this->searchOnTableOrCreate('modes',
											['name' => 'Auditoria Medica Ambulatoria'],
											[]);
										if (!empty($userFile['solicitudLicencia']['estadoNombre'])) {
											switch ($userFile['solicitudLicencia']['estadoNombre']['estadoNombre']) {
												case 'APROBADA':
													$status = $reportTable::GRANTED;
													break;
												case 'PENDIENTE':
													//No hacer nada
													break;
												CASE 'DENEGADA':
												CASE 'RECHAZADA':
													$status = $reportTable::DENIED;
													break;
												CASE 'AUSENTE':
													$status = $reportTable::NRLL;
													break;
												CASE 'JUNTA':
													$modeID = $this->searchOnTableOrCreate('modes',
														['name' => 'Juntas Medicas'],
														[]);
													break;
												CASE 'DOMICILIO':
													$modeID = $this->searchOnTableOrCreate('modes',
														['name' => 'Auditoria Medica Domiciliaria'],
														[]);
													break;
											}
										}

										$licenseTypes = $reportTable::LICENSES;
										$licenseType = array_search('Titular', array_column($licenseTypes, 'name'));

										if (!empty($userFile['solicitudLicencia']['esFamiliar']) == 1) {
											$licenseType = array_search('Cuidado de Familiar Enfermo', array_column($licenseTypes, 'name'));
											// FALTA LOS FAMILIARES. Tambie nse puede sacar la direccion si es domicilio. EJ: 9497383
										}

										$reportID = $this->searchOnTableOrCreate('reports',
											['externalID' => $userFile['id']],
											[
											'patient_id' => $patientID,
											'medicalCenter' => $medicalCenterID,
											'doctor_id' => 1,
											'user_id' => 1,
											'startPathology' => (!empty($userFile['fechaInicio'])) ? new FrozenTime($userFile['fechaInicio']) : $today->format('Y-m-d'),
											'comments' => $userFile['nota'],
											'type' => $licenseType + 1,
											'askedDays' => $userFile['solicitudLicencia']['diasSolicitados'],
											'recommendedDays' => $userFile['diasAprobados'],
											'startLicense' => null,
											'observations' => null,
											'status' => $status,
											'fraud' => 0,
											'mode_id' => $modeID,
											'relativeName' => null,
											'relativeLastname' => null,
											'relativeRelationship' => null,
											'privatedoctor_id' => $privateDoctorID,
											'speciality_id' => $specialityID,
											'cie10_id' => $cie10ID,
											'risk_group' => $userFile['docGrupoRiesgo'],
											'externalID' => $userFile['id'],
										]);

										//Guardo los archivos.
										$dirImg = new Folder($directoryFiles . DS . $file . DS . $file2 . DS . $userFolder . DS . 'img');
										$imgFiles = $dirImg->read(true);
										foreach ($imgFiles[1] as $imgFile) {
											$mimeContentType = mime_content_type($directoryFiles . DS . $file . DS . $file2 . DS . $userFolder . DS . 'img' . DS . $imgFile);

											$path = WWW_ROOT . 'files' . DS;
											if (!file_exists($path) && !is_dir($path)) {
												mkdir($path);
											}

											$path .= $reportID . DS;
											if (!file_exists($path) && !is_dir($path)) {
												mkdir($path);
											}

											$fileID = $this->searchOnTableOrCreate('files',
												[
													'report_id' => $reportID,
													'name' => $imgFile,
												],
												[
													'report_id' => $reportID,
													'name' => $imgFile,
													'type' => $mimeContentType,
													'reportType' => 1,
												]);
										}

									}
								}
							}
							$this->consoleLog('User file reading done.');
						}
					}
				}
			}
		}

		$this->consoleLog('Reading directories done.');
	}

	private function jobRunning($type): bool
	{
		$running = false;
		$jobsTable = $this->fetchTable('Jobs');
		$lastJob = $jobsTable
			->find()
			->where([
				'name' => $type,
				'status IN' => $this->statuses['running']
			])
			->order(['id' => 'desc'])
			->all();

		if (!$lastJob->isEmpty()) {
			$running = true;
		}

		return $running;
	}

	private function jobFinished($type) {
		$running = false;
		$jobsTable = $this->fetchTable('Jobs');
		$lastJob = $jobsTable
			->find()
			->where([
				'name' => $type,
				'status IN' => $this->statuses['finished']
			])
			->order(['id' => 'desc'])
			->first();

		if (!empty($lastJob)) {
			$running = $lastJob->id;
		}

		return $running;
	}

	private function searchOnTableOrCreate($tableName, $where, $creationData) {
		$returnID = null;
		$this->consoleLog('Searching on ' . $tableName . '.');
		$table = $this->fetchTable($tableName);
		$entityExist = $table->find()->where($where)->first();

		if (!empty($entityExist)) {
			$this->consoleLog( $tableName . ' found. ID: ' . $entityExist->id);
			$returnID = $entityExist->id;
		} elseif (!empty($creationData)) {
			$this->consoleLog( $tableName . ' not found. Creating...');
			$newCreation = $this->saveOnTable($tableName, $creationData);
			if ($newCreation) {
				$this->consoleLog( $tableName . ' new record created. ID: ' . $newCreation->id);
				$returnID = $newCreation->id;
			} else {
				$this->consoleLog( $tableName . '  record not created. A problem occurs');
			}
		}

		return $returnID;
	}

	private function saveOnTable($table, $data) {
		if (empty($data)) {
			return;
		}
		try {
			$table = $this->fetchTable($table);
			$tableEntity = $table->newEmptyEntity();
			$tableEntity = $table->patchEntity($tableEntity, $data);
			$result = $table->save($tableEntity);
			if (!$result) {
				debug($tableEntity);
			}
		} catch (\Exception $e) {
			$this->consoleLog('<error>' . $e->getMessage() . '</error>');
			$result = false;
		}


		return $result;
	}

}
