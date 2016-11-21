<?php
set_time_limit(0);

spl_autoload_register(function ($class) {
    $file_name = str_replace('\\', '/', $class) . '.php';
    if (file_exists($file_name)) {
        require_once($file_name);
    } else {
        echo "file is not exist";
    }
});


//$create = new Databases_classes\Create();
//$edit = new Databases_classes\Edit();
//$dblist = new Databases_classes\DB_List();
//$parse = new Parsing\Parse();
$parse = new Parsing\ParseLinks();

//echo "The result of table creation is: " . (integer)$create->createTable('usersssss');
//echo "The result of table creation for MESSAGES is: " . (integer)$create->createTable('message_base', \Databases_classes\Create::MESSAGES);
//echo "The result of table deletion is: " . (integer)$create->deleteTable('usersssss');
//echo "The result of table inserting is: " . (integer)$edit->insertInTable(["table_name" => "usersssss",
//                                                                            "id" => NULL,
//                                                                            "user" => "Odin",
//                                                                            "create_date" => "2016-11-08",
//                                                                            "modified_date" => "2016-11-09",
//                                                                            "status" => 1,
//                                                                            "type" => 0 ]);
//echo "The result of table updating is: " . (integer)$edit->updateTable(["table_name" => "usersssss",
//                                                                            "id" => NULL,
//                                                                            "user" => "Mokachino",
//                                                                            "create_date" => "2016-11-01",
//                                                                            "modified_date" => "2016-11-08",
//                                                                            "status" => 0,
//                                                                            "type" => 4 ]);
//var_export($dblist->getUserById("usersssss", 1));
//$dblist->showArray($dblist->getUserByStatus("usersssss", 0));
