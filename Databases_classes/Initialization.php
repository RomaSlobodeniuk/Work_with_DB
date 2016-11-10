<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 09.11.2016
 * Time: 20:15
 */

namespace Databases_classes;
use ExceptionSystem\ExceptionDataBase;

abstract class Initialization
{
    public $database;
    public $host;
    public $user;
    public $password;
    const MAIN_BASE = 'main_base';

    public function setConnection($host = 'localhost', $user = 'root', $password = '', $database = self::MAIN_BASE)
    {
        $this->database = new \mysqli($this->host = $host, $this->user = $user, $this->password = $password, $base = $database);
        try{
            if($this->database->connect_errno){
                throw new ExceptionDataBase("The database connection hasn't been established!");
            }
        } catch (ExceptionDataBase $error){
            echo $error->connectionError($error->getMessage());
        }

        $this->database->query("SET NAMES 'utf8'");
        return $this->database;
    }

    public function closeConnection()
    {
        try{
            if(!$this->database->close()){
                throw new ExceptionDataBase("The connection hasn't been closed!");
            }
        } catch (ExceptionDataBase $error){
            echo $error->connectionError($error->getMessage());
        }
    }

    public function showArray($input_array){
        echo '<hr><br><pre>';
        var_export($input_array);
        echo '<hr><br></pre>';
    }
}