<?php

namespace Repository;

class RepositoryBase
{
    private $path;
    private $defaultPath;
    private $select = [];

    public function __construct()
    {
        $this->path = getenv('PATHFBOOT', '/var/www/filesBot/');
        $this->defaultPath = $this->path;
    }

    public function setFrom($place)
    {
        $this->path = $this->defaultPath;
        $place = explode('_', $place);

        if (isset($place[1])) {
            $place = implode('/', $place);
        } else {
            $place = $place[0];
        }

        $path = $this->path . $place;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->path = $path;
    }

    public function setFromLogs($place)
    {
        $this->path = getenv('PATHFLOG');
        $place = explode('_', $place);

        if (isset($place[1])) {
            $place = implode('/', $place);
        } else {
            $place = $place[0];
        }

        $path = $this->path . $place;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->path = $path;
    }

    public function insert(array $array = [], $nameFile = 'log.json'): bool
    {
        try {

            $path = $this->path . '/' . $nameFile;
            $oldFile = $this->select($nameFile);
            $oldFile[] = $array;
            $file = fopen($path, 'w');
            fwrite($file, json_encode($oldFile));
            fclose($file);

            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function insetDirect(array $array = [], $nameFile = 'log.json'): bool
    {
        try {

            $path = $this->path . '/' . $nameFile;
            $oldFile = $array;
            $file = fopen($path, 'w');
            fwrite($file, json_encode($oldFile));
            fclose($file);

            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function select($nameFile = 'log.json')
    {
        $path = $this->path . '/' . $nameFile;
        $this->select = [];
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $this->select = json_decode($file, true);
            return $this->select;
        }
        return [];
    }

    public function setColumns(array $columns = []): void
    {
        $this->select = array_map(function ($item) use ($columns) {
            $newItem = [];
            foreach ($columns as $column) {
                $newItem[$column] = $item[$column];
            }
            return $newItem;
        }, $this->select);

        return;
    }

    public function setLimit(int $limit = 1): void
    {
        $this->select = array_slice($this->select, 0, $limit);
    }

    public function delete($nameFile = 'log.json')
    {
        $path = $this->path . '/' . $nameFile;

        if (file_exists($path)) {
            unlink($path);
        }

        //check if the file was deleted
        if (file_exists($path)) {
            echo "Error deleting $path";

            return false;
        }

        return true;
    }

    public function getSelect(): array
    {
        return $this->select ?? [];
    }

    public function updateColumn(array $columnAndValue, $nameFile = 'log.json'): bool
    {
        try{
            $path = $this->path . '/' . $nameFile;
            $oldFile = $this->select($nameFile);
            $newFile = [];
            foreach ($oldFile as $key => $item) {
                foreach ($columnAndValue as $column => $value) {
                    $item[$column] = $value;
                }
                $newFile[] = $item;
            }

            $file = fopen($path, 'w');
            fwrite($file, json_encode($newFile));
            fclose($file);

            return true;
        }catch(\Exception $e){
            return false;
        }

    }

    public function update(array $columnAndValue){
        $this->select = array_map(function ($item) use ($columnAndValue) {
            foreach ($columnAndValue as $column => $value) {
                $item[$column] = $value;
            }
            return $item;
        }, $this->select);
    }

    public function where(array $columnAndValue): void
    {
        $this->select = array_filter($this->select, function ($item) use ($columnAndValue) {
            foreach ($columnAndValue as $column => $value) {
                if ($item[$column] != $value) {
                    return false;
                }
            }
            return true;
        });
    }

    public function whereDifferent(array $columnAndValue): void
    {
        $this->select = array_filter($this->select, function ($item) use ($columnAndValue) {
            foreach ($columnAndValue as $column => $value) {
                if ($item[$column] == $value) {
                    return false;
                }
            }
            return true;
        });
    }
}
