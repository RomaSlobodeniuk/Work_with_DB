<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 09.11.2016
 * Time: 20:16
 */

namespace Databases_classes;
use Databases_classes\Initialization;

class Create extends Initialization
{
    public function createTable($table_name, $type){
        $this->setConnection();
        if($type == self::USERS){
            $result = $this->database->query("CREATE TABLE `{$table_name}` (
                                `id` INTEGER NOT NULL AUTO_INCREMENT,
                                `user` VARCHAR(255),
                                `create_date` DATE,
                                `modified_date` DATE,
                                `status` INTEGER(11),
                                `type` INTEGER (11),
                                PRIMARY KEY (`id`)
                                ) ENGINE = MyISAM;");
        }
        if ($type == self::MESSAGES){
            $result = $this->database->query("CREATE TABLE `{$table_name}` (
                                `id` INTEGER NOT NULL AUTO_INCREMENT,
                                `messages` TEXT,
                                PRIMARY KEY (`id`)
                                ) ENGINE = MyISAM;");
        }
        $this->closeConnection();
        return $result;
    }

    public function deleteTable($table_name){
        $this->setConnection();
        $result = $this->database->query("DROP TABLE `{$table_name}`");
        $this->closeConnection();
        return $result;
    }

}