<?php

namespace Service;

class LogService {

    private $typeLog = ['Execution', 'Failure', 'Success', 'Treatment', 'Info'];
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

            if(!file_exists($this->path."\\{$path}\\{$type}")){
                mkdir($this->path."\\{$path}\\{$type}");
            }
        }
    }

    private function creatingLog($log,$type){
        $file = $this->path."\\Logs\\{$type}\\log.txt";

        if(file_exists($file)){
            $contentfile = file_get_contents($file);
            $json = json_decode($contentfile);
            $content = array_merge($json, $log);
        }else{
            $content = $log;
        }

        file_put_contents($file, json_encode($content));
    }
}
