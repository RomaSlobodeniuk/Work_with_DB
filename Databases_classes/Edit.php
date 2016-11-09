<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 09.11.2016
 * Time: 20:17
 */

namespace Databases_classes;
use Databases_classes\Initialization;

class Edit extends Initialization
{
    public function insertInTable($data){
        $this->setConnection();
        $result = $this->database->query("INSERT INTO `{$data['table_name']}`
                                SET `id` = '{$data['id']}',
                                  `user` = '{$data['user']}',
                                  `create_date` = '{$data['create_date']}',
                                  `modified_date` = '{$data['modified_date']}',
                                  `status` = '{$data['status']}',
                                  `type` = '{$data['type']}'");
        $this->closeConnection();
        return $result;
    }

    public function updateTable($data){
        $this->setConnection();
        $result = $this->database->query("UPDATE `{$data['table_name']}`
                                  SET `id` = '{$data['id']}',
                                  `user` = '{$data['user']}',
                                  `create_date` = '{$data['create_date']}',
                                  `modified_date` = '{$data['modified_date']}',
                                  `status` = '{$data['status']}',
                                  `type` = '{$data['type']}'");
        return $result;
    }

    public function clearTable($table_name){
        $this->setConnection();
        $this->database->query("TRUNCATE TABLE `{$table_name}`");
        $this->closeConnection();
    }

}