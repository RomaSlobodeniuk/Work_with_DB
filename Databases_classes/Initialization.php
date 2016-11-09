<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 09.11.2016
 * Time: 20:15
 */

namespace Databases_classes;


abstract class Initialization
{
    public $database;
    public $host;
    public $user;
    public $password;
    const MAIN_BASE = 'main_base';

    public function setConnection($host = 'localhost', $user = 'root', $password = '', $database = self::MAIN_BASE){
        $this->database = new \mysqli($this->host = $host, $this->user = $user, $this->password = $password, $base = $database);
        $this->database->query("SET NAMES 'utf8'");
        return $this->database;
    }

    public function closeConnection(){
        $this->database->close();
    }



}