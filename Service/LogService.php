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

    public function savePageActual($page,$content,$way, $termino = false) {

        //verificar se existe e se existir exclui
        $file = $this->path."/Logs/Pages/log.txt";

        if(file_exists($file)){
            unlink($file);
        }

        $actualPage = $page;
        $processedPage = $page - 1;
        $processedRecord = $page * 20;
        $erroe = $content->error ?? false;
        $message = $content->message ?? '';

        $json = [
            'totalPages' => $content->totalPages,
            'totalRecords' => $content->totalElements,
            'actualPage' => $actualPage,
            'processedPage' => $processedPage,
            'processedRecord' => $processedRecord,
            'error' => $erroe,
            'message' => $message,
            'termino' => $termino,
        ];

        $this->setLog($json, 'Pages', $way);
	    $this->saveOnDatabase($json);
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
			$sql ="UPDATE jobs SET message= '" . $message . "'". $extraSQL . " WHERE id=" . $id . ";";
			$result = $mysqli->query($sql);
		}
	}
}
