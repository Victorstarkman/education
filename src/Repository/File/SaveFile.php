<?php

namespace Repository\File;


class SaveFile
{
    private $time;
    private $pathDefault;

    public function __construct()
    {
        $this->pathDefault = getenv('PATHFBOOT', '/var/www/filesBot/');
        $this->time = date('H_i_s');
        $this->createDefaultsPath();
    }

    public function createFilesPages(int $page, string $json)
    {
        $this->createPagesPath($page);
        $this->createPageJsonFile($page, $json);
    }

    private function createPageJsonFile(int $page, string $json)
    {
        $date = date('Y-m-d');

        $file = $this->pathDefault . "pages/" . "/$page/" . $date . '_' . uniqid(rand(0, 1000)) . ".json";

        file_put_contents($file, $json);
    }

    public function createFilesSolicitedJson(int $page, int $id, int $idReg, string $json)
    {
        $this->createUserPath($page, $id, $idReg);
        $this->createJsonFile($page, $id, $idReg, $json);
    }

    public function getPathPageDate()
    {
        $paths = scandir($this->pathDefault . 'pages/');
        $paths = array_diff($paths, array('.', '..'));

        if (empty($paths)) {
            return [];
        }

        array_walk($paths, function (&$value, $key) {
            $value = $this->pathDefault . 'pages/' . $value . '/';
        });
        return $paths;
    }

    public function getPathPages($path)
    {
        $paths = scandir($path);
        $paths = array_diff($paths, array('.', '..'));

        if (empty($paths)) {
            return [];
        }

        array_walk($paths, function (&$value, $key) use ($path) {
            $value = $path . '/' . $value . '/';
        });

        return $paths;
    }

    public function getPathFilesPage($path)
    {
        $paths = scandir($path);
        $paths = array_diff($paths, array('.', '..'));

        if (empty($paths)) {
            return [];
        }

        array_walk($paths, function (&$value, $key) use ($path) {
            $value = $path . $value;
        });

        return $paths;
    }

    public function getFile($path)
    {
        return file_get_contents($path);
    }

    private function createJsonFile(int $page, int $id, int $idReg, string $json)
    {
        $path = $this->pathDefault . "users/" . date('Y-m-d') . "/$page/$id/json/$idReg.json";
        file_put_contents($path, $json);
    }

    public function createImgFile(int $page, string $nameImg, int $idReg, string $img)
    {
        $path = $this->pathDefault . "users/" . date('Y-m-d') . "/$page/$idReg/img/$nameImg.jpg";

        file_put_contents($path, $img);
    }

    private function createUserPath(int $page, int $idReg)
    {

        $date = date('Y-m-d');

        $paths = [
            "users/$date/$page/$idReg",
            "users/$date/$page/$idReg/json",
            "users/$date/$page/$idReg/img"
        ];

        foreach ($paths as $path) {
            if (!file_exists($this->pathDefault . $path)) {
                mkdir($this->pathDefault .  $path, 0777, true);
            }
        }
    }

    private function createPagesPath(int $page)
    {

        $date = date('Y-m-d');

        $paths = [
            "pages/",
            "pages/$page",
            "solicited/",
            "solicited/$date"
        ];

        foreach ($paths as $path) {
            if (!file_exists($this->pathDefault .  $path)) {
                mkdir($this->pathDefault .  $path, 0777, true);
            }
        }
    }

    private function createDefaultsPath()
    {
        $paths = [
            'users',
            'pages'
        ];

        foreach ($paths as $path) {
            if (!file_exists($this->pathDefault .  $path)) {
                mkdir($this->pathDefault .  $path);
            }
        }
    }

    public function deleteAndMovePathAndFilies(int $page)
    {
        $path = $this->pathDefault . 'pages/' . $page;
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->deleteAndMovePathAndFilies($path . "/" . $file);
            } else {
                //move file 
                rename($file, $this->pathDefault . 'users/' . date('Y-m-d') . '/' . $page . '/' . basename($file));
            }
        }

        if ($this->checkEmptyPath($path)) {
            echo "delete path: " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->deleteAndMovePathAndFilies($path);
        }
    }

    public function deletePathAndFilies(int $page)
    {
        $path = $this->pathDefault . 'pages/' . $page;
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->deleteAndMovePathAndFilies($path . "/" . $file);
            } else {
                //delete file
                unlink($file);
            }
        }

        if ($this->checkEmptyPath($path)) {
            echo "delete path: " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->deleteAndMovePathAndFilies($path);
        }
    }
    public function movePathUsersForSolicited()
    {
        $path = $this->pathDefault . 'users/' . date('Y-m-d') . '/';
        $files = glob($path . '/*'); // get all file names
        $time = $this->time;
        foreach ($files as $file) { // iterate files
            if (!file_exists($this->pathDefault . 'solicited/' . date('Y-m-d') . '/' . $time . '/')) {
                mkdir($this->pathDefault . 'solicited/' . date('Y-m-d') . '/' . $time . '/', 0777, true);
            }

            rename($file, $this->pathDefault . 'solicited/' . date('Y-m-d') . '/' . $time . '/' . basename($file));
        }

        if ($this->checkEmptyPath($path)) {
            echo "delete path: " . $path . PHP_EOL;
            rmdir($path);
        } else {
            $this->movePathUsersForSolicited($path);
        }
    }

    private function checkEmptyPath($path)
    {
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (!is_file($file)) {
                $this->checkEmptyPath($path . "/" . $file);
            } else {
                return false;
            }
        }

        return true;
    }
}
