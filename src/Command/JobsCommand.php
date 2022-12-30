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

		$this->statuses = [
			'running' => 1,
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

		exec("cd ..;php Service/Service/Bot/Bot.php");
	}

	private function checkInitCommand() {
		$lastId = (int) $this->jobFinished('scrapperInit');
		if ($lastId > 0 && !$this->jobRunning('scrapperProcessor')) {
			$jobsTable = $this->fetchTable('Jobs');
			$job = $jobsTable->get($lastId);
			$job->status = $this->statuses['completed'];
			$jobsTable->save($job);
			$this->saveOnTable('jobs', [
				'name' => 'scrapperProcessor',
				'status' => 0,
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
		$today = $today->format('Y-m-d');
		$directoryFiles = ROOT . DS . 'Service' . DS . 'File' . DS . 'Users' . DS . $today;

		if (!file_exists($directoryFiles)) {
			$this->io->abort('Directory not found. Aborting command. Directory path: ' . $directoryFiles);
		}

		$dir = new Folder($directoryFiles);
		$files = $dir->read(true, ['files']);
		// Recorro las paginas
		$this->consoleLog('Reading directories.');
		$data = [
			'totalPages' => count($files[0]),
			'actualPage' => 1,
			'processedPage' => 0,
			'recordsProcessed' => 0,
			'newUsers' => 0,
			'usersProcessed' => 0,
			'userError' => 0,
			'newReports' => 0,
			'reportsProcessed' => 0,
			'reportsError' => 0,
			'newFiles' => 0,
			'filesProcessed' => 0,
			'ended' => false,
		];
		$this->updateJob('scrapperProcessor', $data);
		foreach ($files[0] as $file) {
			$dir2 = new Folder($directoryFiles . DS . $file);
			$files2 = $dir2->read(true);
			//Recorro los registros
			foreach ($files2[0] as $file2) {
				$dir3 = new Folder($directoryFiles . DS . $file . DS . $file2);
				$files3 = $dir3->read(true);
				foreach ($files3 as $file3) {
					if (!empty($file3)) {
						$jsonUserPath = $directoryFiles . DS . $file . DS . $file2 . DS . 'json/jsonOrinResponse.json';
						$this->consoleLog('Reading user file:' . $jsonUserPath);
						if (file_exists($jsonUserPath)) {
							$userFile = json_decode(file_get_contents($jsonUserPath), true);
							if (!empty($userFile)) {
								if (!empty($userFile['solicitudLicencia'])) {
									if (!empty($userFile['solicitudLicencia']['agente'])) {
										$cuil = $this->trim($userFile['solicitudLicencia']['agente']['cuil']);
										$document = $this->trim($userFile['solicitudLicencia']['agente']['documento']);
										$searchWhere = [];
										if (!empty($cuil) && !empty($document))  {
											$searchWhere = [
												'OR' => [
													'cuil' => $this->trim($userFile['solicitudLicencia']['agente']['cuil']),
													'document' => $this->trim($userFile['solicitudLicencia']['agente']['documento'])
												]
											];
										} elseif (!empty($cuil)) {
											$searchWhere = ['cuil' => $this->trim($userFile['solicitudLicencia']['agente']['cuil'])];
										} elseif (!empty($document)) {
											$searchWhere = ['document' => $this->trim($userFile['solicitudLicencia']['agente']['documento'])];
										}
										$dirJob = new Folder($directoryFiles . DS . $file . DS . $file2 . DS . 'json' . DS  . 'consultarDatos');
										$filesJob = $dirJob->read(true);
										$jobOfPatient = null;
										if (!empty($filesJob[1])) {
											foreach ($filesJob[1] as $fileJob) {
												if (!is_null($jobOfPatient)) {
													continue;
												}
												$getDataJob = json_decode(file_get_contents($directoryFiles . DS . $file . DS . $file2 . DS . 'json' . DS  . 'consultarDatos' . DS . $fileJob), true);
												$jobOfPatient = $getDataJob['codigoRegEstat'];
											}
										}
										$patientResponse = $this->searchOnTableOrCreate('Patients', $searchWhere,
											[
												'name' => $this->trim($userFile['solicitudLicencia']['agente']['apellidoNombre']),
												'email' => $this->trim($userFile['solicitudLicencia']['agente']['emailAlternativo']),
												'official_email' => $this->trim($userFile['solicitudLicencia']['agente']['email']),
												'cuil' => $this->trim($userFile['solicitudLicencia']['agente']['cuil']),
												'document' => $this->trim($userFile['solicitudLicencia']['agente']['documento']),
												'phone' => $userFile['solicitudLicencia']['agente']['area'] . '' . $userFile['solicitudLicencia']['agente']['numCelular'],
												'company_id' => 1,
												'job' => $jobOfPatient,
												'externalID' => $userFile['solicitudLicencia']['agente']['id'],
											]
										);


										if ($patientResponse['error']) {
											$data['userError']++;
											continue;
										}
										$data['usersProcessed']++;
										if ($patientResponse['created']) {
											$data['newUsers']++;
										}

										$patientID = $patientResponse['id'];

										if (!empty($userFile['diagnostico'])) {
											$medicalCenterID = null;
											if (!empty($userFile['solicitudLicencia']['agente']['centroAuditoria'])) {
												$this->consoleLog('Searching Medical Center.');
												$userMedicalCenterInfo = $userFile['solicitudLicencia']['agente']['centroAuditoria'];
												$medicalCenterResponse = $this->searchOnTableOrCreate(
													'MedicalCenters' ,
													['district' => $this->trim($userMedicalCenterInfo['razonSocial'])],
													['district' => $this->trim($userMedicalCenterInfo['razonSocial'])]);
												if (!$medicalCenterResponse['error']) {
													$medicalCenterID = $medicalCenterResponse['id'];
												}

											}

											$cie10ID = null;
											if (!empty($userFile['diagnostico']['idDiagnostico'])) {
												$cie10DResponse = $this->searchOnTableOrCreate(
													'Cie10' ,
													['code' => $userFile['diagnostico']['idDiagnostico']],
													[
														'code' => $userFile['diagnostico']['idDiagnostico'],
														'name' => $this->trim($userFile['diagnostico']['detalle']),
													],
												);
												if (!$cie10DResponse['error']) {
													$cie10ID = $cie10DResponse['id'];
												}
											}

											$privateDoctorID = null;
											if (!empty($userFile['solicitudLicencia']['medicoParticular'])) {
												$doctorData = $userFile['solicitudLicencia']['medicoParticular'];
												$privateDoctorResponse = $this->searchOnTableOrCreate(
													'privatedoctors' ,
													[
														'OR' => [
															'license' => $doctorData['matriculaProvincial'],
															'licenseNational' => $doctorData['matriculaNacional'],
														]
													],
													[
														'name' => $this->trim($doctorData['nombre']),
														'lastname' => $this->trim($doctorData['apellido']),
														'license' => ($doctorData['matriculaProvincial'] > 0) ? $doctorData['matriculaProvincial'] : null,
														'licenseNational' => ($doctorData['matriculaNacional'] > 0) ? $doctorData['matriculaNacional'] : null,
													],
												);

												if (!$privateDoctorResponse['error']) {
													$privateDoctorID = $privateDoctorResponse['id'];
												}

												$specialityResponse = $this->searchOnTableOrCreate('specialties',
													['name' => $doctorData['especialidad']['especialidad']],
													['name' => $doctorData['especialidad']['especialidad']]);

												if (!$specialityResponse['error']) {
													$specialityID = $specialityResponse['id'];
												}
											}


											$reportTable = $this->fetchTable('Reports');
											$status = $reportTable::ACTIVE;
											$modeResponse = $this->searchOnTableOrCreate('modes',
												['name' => 'Auditoria Medica Ambulatoria'],
												[]);
											if (!$modeResponse['error']) {
												$modeID = $modeResponse['id'];
											}

											if (!empty($userFile['estadoNombre'])) {
												switch ($userFile['estadoNombre']['estadoNombre']) {
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
														$modeResponse = $this->searchOnTableOrCreate('modes',
															['name' => 'Juntas Medicas'],
															[]);
														if (!$modeResponse['error']) {
															$modeID = $modeResponse['id'];
														}
														break;
													CASE 'DOMICILIO':
														$modeResponse = $this->searchOnTableOrCreate('modes',
															['name' => 'Auditoria Medica Domiciliaria'],
															[]);
														if (!$modeResponse['error']) {
															$modeID = $modeResponse['id'];
														}
														break;
												}
											}
											$licenseTypes = $reportTable::LICENSES;
											$licenseType = array_search('Titular', array_column($licenseTypes, 'name'));

											if (!empty($userFile['solicitudLicencia']['esFamiliar']) == 1) {
												$licenseType = array_search('Cuidado de Familiar Enfermo', array_column($licenseTypes, 'name'));
												// FALTA LOS FAMILIARES. Tambie nse puede sacar la direccion si es domicilio. EJ: 9497383
											}

											$reportResponse = $this->searchOnTableOrCreate('reports',
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
													'externalID' => $userFile['solicitudLicencia']['id'],
													'created' => $userFile['solicitudLicencia']['fechaCreacion'],
													'modified' => $userFile['solicitudLicencia']['fechaCreacion'],
												]);




											if (!$reportResponse['error']) {
												$reportID = $reportResponse['id'];
												$data['reportsProcessed']++;

												if ($patientResponse['created']) {
													$data['newReports']++;
												}
											} else {
												$data['reportsError']++;
												continue;
											}
											//Guardo los archivos.
											$dirImg = new Folder($directoryFiles . DS . $file . DS . $file2 . DS . 'img');
											$imgFiles = $dirImg->read(true);
											foreach ($imgFiles[1] as $imgFile) {
												$mimeContentType = mime_content_type($directoryFiles . DS . $file . DS . $file2 . DS . 'img' . DS . $imgFile);

												$path = WWW_ROOT . 'files' . DS;
												if (!file_exists($path) && !is_dir($path)) {
													mkdir($path);
												}

												$path .= $reportID . DS;
												if (!file_exists($path) && !is_dir($path)) {
													mkdir($path);
												}
												$copy = copy($directoryFiles . DS . $file . DS . $file2 . DS . 'img' . DS . $imgFile, $path . DS . $imgFile);
												if ($copy) {
													$fileResponse = $this->searchOnTableOrCreate('files',
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

													if (!$fileResponse['error']) {
														$data['filesProcessed']++;

														if ($fileResponse['created']) {
															$data['newFiles']++;
														}
													}
												}
											}

										}
									}
								}
							}
						}

						$data['recordsProcessed']++;
						$this->updateJob('scrapperProcessor', $data);
						$this->consoleLog('User file reading done.');
					}
				}
			}
			$data['processedPage']++;
			$this->updateJob('scrapperProcessor', $data);
		}
		$data['ended'] = true;
		$this->updateJob('scrapperProcessor', $data);
		$this->consoleLog('Reading directories done.');
	}

	private function updateJob($type, $data) {
		$jobTable = $this->fetchTable('jobs');
		switch ($type) {
			case 'scrapperProcessor':
				$lastJob = $jobTable->find()->where(['name' => 'scrapperProcessor', 'status IN' => [1,0]])->order(['id' => 'DESC'])->first();
				$jobToUpdate = $jobTable->get($lastJob->id);
				if ($jobToUpdate->status == 0) {
					$jobToUpdate->status = 1;
				}
				$jobToUpdate->message = json_encode($data);
				if ($data['ended']) {
					$jobToUpdate->status = $this->statuses['completed'];
				}
				$jobTable->save($jobToUpdate);
				break;
		}
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
		$returnData = [
			'id' => null,
			'error' => false,
			'created' => false,
			'msg' => '',
		];
		$this->consoleLog('Searching on ' . $tableName . '.');
		$table = $this->fetchTable($tableName);
		$entityExist = $table->find()->where($where)->first();

		if (!empty($entityExist)) {
			$this->consoleLog( $tableName . ' found. ID: ' . $entityExist->id);
			$returnData['id'] = $entityExist->id;
		} elseif (!empty($creationData)) {
			$this->consoleLog( $tableName . ' not found. Creating...');
			$returnData['created'] = true;
			$newCreation = $this->saveOnTable($tableName, $creationData);
			if (!$newCreation['error']) {
				$this->consoleLog( $tableName . ' new record created. ID: ' . $newCreation['creationEntity']->id);
				$returnData['id'] = $newCreation['creationEntity']->id;
			} else {
				$returnData['false'] = true;
				$returnData['msg'] = $newCreation['msg'];
				$this->consoleLog( $tableName . '  record not created. A problem occurs');
			}
		}

		return $returnData;
	}

	private function saveOnTable($table, $data) {
		if (empty($data)) {
			return;
		}
		$response = [
			'error' => false,
			'creationEntity' => null,
			'msg' => '',
		];
		try {
			$tableName = $table;
			$table = $this->fetchTable($tableName);
			$tableEntity = $table->newEmptyEntity();
			$tableEntity = $table->patchEntity($tableEntity, $data);
			$response['creationEntity'] = $table->save($tableEntity);
			if (!$response['creationEntity']) {
				//debug($tableEntity);
				$response['error'] = true;
				$response['msg'] = 'Error al generar: ' . $tableName . 'Data: ' . json_encode($data);
			}
		} catch (\Exception $e) {
			$response['error'] = true;
			debug('Error');
			debug($e);
			//	$response['msg'] = $e->getMessage();
			//$this->consoleLog('<error>' . $e->getMessage() . '</error>');
		}


		return $response;
	}

	private function trim($string) {
		return (!empty($string) && is_string($string)) ? trim($string) : $string;
	}

}
