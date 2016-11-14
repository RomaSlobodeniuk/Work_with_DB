<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 10.11.2016
 * Time: 21:54
 */

namespace Parsing;
use Databases_classes\Initialization;
use Databases_classes\Create;
use Databases_classes\Edit;
use Databases_classes\DB_List;

class Parse
{
    const INITIAL_PAGE = 'http://bash.im/';
    public static $initial_path = __DIR__ . '\data\input_data.txt';
    public $number_of_current_page;
    public $current_page;
    public $page_array = [];
    public $data = [];
    const CURRENT_PAGE = 1;
    const PER_PAGE = 50;

    public function __construct()
    {
        $this->setCurrentPage(self::$initial_path);
        $this->parseData(self::$initial_path);
        $this->showContent();
    }

    public function getContentForParsing($file_destination)
    {
//        return iconv('CP1251', 'UTF-8', file_get_contents($file_destination, true));
        return file_get_contents($file_destination, true);
    }

    public function setCurrentPage($initial_path){
        $search_content = $this->getContentForParsing($initial_path);
        $pattern = '/<div class="pager">(.*)<\/div>/i';
        $match = preg_match($pattern, $search_content, $matches);

        $search_content = $matches[0];
        $pattern = '/value="([0-9]{0,5})"/i';
        $match = preg_match($pattern, $search_content, $matches);
        $this->number_of_current_page = $matches[1];
    }

    public function parseData($path_for_parsing){
        $temp_array_with_messages = array();
        for($i = $this->number_of_current_page; $i > $this->number_of_current_page - 5; $i--){
            $this->current_page = self::INITIAL_PAGE . 'index/' . $i;
            array_push($this->page_array, $this->current_page);

//            $search_content = $this->getContentForParsing($this->current_page); // if we want to parse content from "bash.im";
            $search_content = $this->getContentForParsing($path_for_parsing); // if we wanna parse just for testing from file: Parsing/data/input_data.txt;
            $pattern = '/<div class="text">(.*)<\/div>/i';
            $match = preg_match_all($pattern, $search_content, $matches);
            foreach ($matches[0] as $list) {
                array_push($temp_array_with_messages, $list);
            }
        }
        $array_for_recording_to_DB = array();
        $array_for_recording_to_DB['type'] = Initialization::MESSAGES;
        $array_for_recording_to_DB['table_name'] = 'message_base';
        array_push($array_for_recording_to_DB, $temp_array_with_messages);

        $create = new Create();
        $result = (integer)$create->createTable('message_base', Create::MESSAGES);
        $edit = new Edit();
        $result = (integer)$edit->insertInTable($array_for_recording_to_DB);
        $db_list = new DB_List();
        $this->data = $db_list->getAllItemsFromDB($array_for_recording_to_DB['table_name']);
    }

    public function showContent()
    {
        $start_end_pagination_array = $this->getPaginationContent();
        $start = $start_end_pagination_array[0];
        $end = $start_end_pagination_array[1];
        $pagination = $start_end_pagination_array[2];

        require_once ('template/view.php');
    }

    public function getPaginationContent()
    {
        if (isset($_GET['Cpag']) and is_numeric($_GET['Cpag'])) {
            $current = $_GET['Cpag'];
        } else {
            $current = self::CURRENT_PAGE;
        }
        $per_page = self::PER_PAGE;

        $pagination = function ($all) use ($per_page, $current) {
            $pag = '<ul class="pagination">';
            for ($i = 0, $j = 0; $i < $all; $i += $per_page, $j++) {
                if ($current == $j + 1) {
                    $pag .= '<li class="active"><span>' . ($j + 1) . '</span></li>';
                } else {
                    $pag .= '<li><a href="page-' . ($j + 1) . '.html">' . ($j + 1) . '</a></li>';
                }
            }
            $pag .= '</ul>';
            return $pag;
        };

        $all_count = count($this->data);
        $start = ($current - 1) * $per_page;
        $end = (($current * $per_page) < $all_count) ? $current * $per_page : $all_count;

        $start_end_pagination_array = array();
        array_push($start_end_pagination_array, $start, $end, $pagination($all_count));

        return $start_end_pagination_array;
    }

    public function showArray($array){
        echo '<hr><br><pre>';
        var_export($array);
        echo '<hr><br></pre>';
    }
}