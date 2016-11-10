<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 10.11.2016
 * Time: 21:19
 */

namespace ExceptionSystem;

class ExceptionDataBase extends \Exception
{
    public function connectionError($input_error){
        return "Here is a message: {$input_error}";
    }

}