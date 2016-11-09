<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 09.11.2016
 * Time: 20:18
 */

namespace Databases_classes;
use Databases_classes\Initialization;

class DB_List extends Initialization
{
    public function getUserById($table_name, $id){
        $this->setConnection();
        $query = "SELECT * FROM `{$table_name}` WHERE `id` = '{$id}'";
        $result = $this->database->query($query);
        $row = $result->fetch_assoc();
        $this->closeConnection();
        return $row;
    }
}