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
        $this->number_of_current_page = $matches[1]; // We search for the value of the current page, then we'll use it in the function "parseData";
    }

    public function parseData($path_for_parsing){
        $temp_array_with_messages = array();
        for($i = $this->number_of_current_page; $i > $this->number_of_current_page - 5; $i--){
            $this->current_page = self::INITIAL_PAGE . 'index/' . $i; // we construct the page path which we are going to parse in further;
            array_push($this->page_array, $this->current_page); // we push every constructed path into array to show each on the pages;

//            $search_content = $this->getContentForParsing($this->current_page); // if we want to parse content from "bash.im";
            $search_content = $this->getContentForParsing($path_for_parsing); // if we wanna parse just for testing from file: Parsing/data/input_data.txt;
            $pattern = '/<div class="text">(.*)<\/div>/i';
            $match = preg_match_all($pattern, $search_content, $matches); // we're searching for matches to full an array with messages;
            foreach ($matches[0] as $list) {
                array_push($temp_array_with_messages, $list); // now we're pushing these messages one by another for every cycle step, it's because of we want to get all the messages from all the pages we have;
            }
        }
        $array_for_recording_to_DB = array();
        $array_for_recording_to_DB['type'] = Initialization::MESSAGES; // We full out an array preparing it for the messages inserting into DB;
        $array_for_recording_to_DB['table_name'] = 'message_base'; // We full out an array preparing it for the messages inserting into DB;
        array_push($array_for_recording_to_DB, $temp_array_with_messages); // We full out an array preparing it for the messages inserting into DB;

        $create = new Create();
        $result = (integer)$create->createTable('message_base', Create::MESSAGES); // We create a table here, but if there is the table already - it won't be created again;
        $edit = new Edit();
        $result = (integer)$edit->insertInTable($array_for_recording_to_DB); // after that we insert our prepared messages into DB, and again, if there are records - current operation won't be executed again;
        $db_list = new DB_List();
        $this->data = $db_list->getAllItemsFromDB($array_for_recording_to_DB['table_name']); // at last we get all the messages from DB to show them on the pages (we'll use "$this->data" to getting messages to parse them in the cycle in a "view.php");
    }

    public function showContent()
    {
        $start_end_pagination_array = $this->getPaginationContent(); // We're preparing the pagination data here which will be used in the cycle "for" in a "view.php";
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