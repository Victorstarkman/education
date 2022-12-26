<?php

namespace Service;

class LogService {

    private $typeLog = ['Execution', 'Failure', 'Success', 'Treatment', 'Info', 'Pages'];
    private $path;

    public function __construct($path){
        $this->path = $path;
        $this->creatingPath();
    }

    public function setLog($log, $type, $way){

        if(empty($log) || ! is_array($log)){
            $log = [
                'date' => date('Y-m-d H:i:s'),
                'message' => "the log needs to be a json",
                'way' => $way
            ];

            $this->creatingLog($log, 'Failure');
            throw new \Exception('the log needs to be a json');
        }

        if(! in_array($type, $this->typeLog)){
            $log = [
                'date' => date('Y-m-d H:i:s'),
                'message' => "the type of log is not valid, types available: ". implode(', ', $this->typeLog),
                'way' => $way
            ];

            $this->creatingLog($log, 'Failure');
            throw new \Exception('the type of log is not valid, types available: '. implode(', ', $this->typeLog));
        }

        $log['way'] = $way;
        $log = [$log];
        $this->creatingLog($log, $type);
    }

    private function creatingPath(){
        $path = "Logs";

        if(!file_exists($path)){
            mkdir($path);
        }

        foreach ($this->typeLog as $type){

            if(!file_exists($this->path."/{$path}/{$type}")){
                mkdir($this->path."/{$path}/{$type}");
            }
        }
    }

    private function creatingLog($log,$type){

        if($type != 'Success' && $type != 'Pages') {
            $date = date('Y-m-d');
            $file = $this->path."/Logs/{$type}/log_{$date}.txt";
        }else{
            $file = $this->path."/Logs/{$type}/log.txt";
        }

        if(file_exists($file)){
            $contentfile = file_get_contents($file);
            $json = json_decode($contentfile);
            $content = array_merge($json, $log);
        }else{
            $content = $log;
        }

        file_put_contents($file, json_encode($content));
    }

    public function savePageActual($page = false, $content = false, $way = false, $termino = false, $recordsSalteados = 0) {

        //verificar se existe e se existir exclui
        $file = $this->path."/Logs/Pages/log.txt";

        if(file_exists($file)){
            //get content
            $contentfile = file_get_contents($file);
            $json = json_decode($contentfile)[0];

            //verificar se o page atual é maior que o page processado
            if($page <= $json->processedPage){
                $termino = true;
            }else{
                $termino = false;
            }

            //get records salteados e erro
            if(isset($json->recordsSalteados))
                $recordsSalteadosExiti = $json->recordsSalteados;
            else
                $recordsSalteadosExiti = 0;

            if(isset($json->error))
                $error = $json->error;
            else
                $error = false;

            if(isset($json->message))
                $message = $json->message;
            else
                $message = '';

            if(isset($json->termino))
                $termino = $json->termino;
            else
                $termino = false;

            if(isset($json->actualPage))
                $actualPage = $json->actualPage;
            else
                $actualPage = 0;

            if(isset($json->processedPage))
                $processedPage = $json->processedPage;
            else
                $processedPage = 0;

            if(isset($json->processedRecord))
                $processedRecord = $json->processedRecord;
            else
                $processedRecord = 0;

            if(isset($json->totalPages))
                $totalPages = $json->totalPages;
            else
                $totalPages = 0;

            if(isset($json->totalRecords))
                $totalRecords = $json->totalRecords;
            else
                $totalRecords = 0;



            //excluir arquivo
            unlink($file);
        }

        if($page != false && $content != false){
            $actualPage = $page;
            $processedPage = $page - 1;
            $processedRecord = $page * 20;
            $error = $content->error ?? false;
            $message = $content->message ?? '';
            $totalPages = $content->totalPages ?? 0;
            $totalRecords =  is_object($content) ? $content->totalElements : 0;

            if($recordsSalteados > 0){
                $processedRecord = $processedRecord - $recordsSalteados;
                if(isset($recordsSalteadosExiti))
                    $recordsSalteados = $recordsSalteados + $recordsSalteadosExiti;
                else
                    $recordsSalteados = $recordsSalteados;
            }else{
                if(isset($recordsSalteadosExiti))
                    $recordsSalteados = $recordsSalteadosExiti;
                else
                    $recordsSalteados = 0;
            }

        }else{
            if($recordsSalteados > 0){
                $processedRecord = $processedRecord - $recordsSalteados;
                if(isset($recordsSalteadosExiti))
                    $recordsSalteados = $recordsSalteados + $recordsSalteadosExiti;
                else
                    $recordsSalteados = $recordsSalteados;
            }else{
                if(isset($recordsSalteadosExiti))
                    $recordsSalteados = $recordsSalteadosExiti;
                else
                    $recordsSalteados = 0;
            }
        }

        $json = [
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords,
            'actualPage' => $actualPage,
            'processedPage' => $processedPage,
            'processedRecord' => $processedRecord,
            'error' => $error,
            'message' => $message,
            'termino' => $termino,
            'recordsSalteados' => $recordsSalteados
        ];

        $this->setLog($json, 'Pages', $way);
	    $this->saveOnDatabase($json);
    }

    public function getPages() {
        $file = $this->path."/Logs/Pages/log.txt";
        if(file_exists($file)){
            $contentfile = file_get_contents($file);
            $json = json_decode($contentfile)[0];
            return $json;
        }else{
            return false;
        }
    }

	private function saveOnDatabase($json) {
		$config = include 'config.php';
		$mysqli = mysqli_connect($config['hostname'], $config['user'], $config['password'], $config['database'])
		or die('No se pudo conectar: ' . mysqli_error());
		$sql = "SELECT * FROM jobs WHERE name ='scrapperInit' and status = 1 order by id DESC LIMIT 1";
		$id = null;
		if ($result = $mysqli->query($sql)) {
			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$id = $row['id'];
			}
		}

		if (!is_null($id)) {
			$extraSQL = '';
			if ($json['termino']) {
				$extraSQL = ' , status=2';
			}

			if ($json['error']) {
				$extraSQL = ' , status=3';
			}

			$message = json_encode($json);
			$sql ="UPDATE jobs SET modified=CONVERT_TZ(NOW(),'SYSTEM','UTC'), message= '" . $message . "'". $extraSQL . " WHERE id=" . $id . ";";
			$result = $mysqli->query($sql);
		}
	}
}
