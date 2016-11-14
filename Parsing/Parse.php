<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 10.11.2016
 * Time: 21:54
 */

namespace Parsing;

class Parse
{
    const INITIAL_PAGE = 'http://bash.im/';
    public static $initial_path = __DIR__ . '\data\input_data.txt';
    public $number_of_current_page;
    public $current_page;
    public $data = [];
    const CURRENT_PAGE = 1;
    const PER_PAGE = 50;

    public function __construct()
    {
        $this->setCurrentPage(self::$initial_path);
        $this->parseData();
        $this->showContent();


//        $this->parseData();

//        $this->getContentToParsing($this->page_path);
//        $this->saveDataToTxt($this->data);


//        if ($this->data !== '') {
//            $this->showData();
//        }
    }

    public function setCurrentPage($initial_path){
        $search_content = $this->getContentToParsing($initial_path);
        $pattern = '/<div class="pager">(.*)<\/div>/i';
        $match = preg_match($pattern, $search_content, $matches);
//        $this->showArray($matches);

        $search_content = $matches[0];
        $pattern = '/value="([0-9]{0,5})"/i';
        $match = preg_match($pattern, $search_content, $matches);
        $this->number_of_current_page = $matches[1];
//        $this->current_page = self::INITIAL_PAGE . 'index/' . $matches[1];

//        echo $this->number_of_current_page;
//        $this->showArray($matches);
    }

    public function getContentToParsing($file_destination)
    {
//        return iconv('CP1251', 'UTF-8', file_get_contents($file_destination, true));
        return file_get_contents($file_destination, true);
    }

    public function showArray($array){
        echo '<hr><br><pre>';
        var_export($array);
        echo '<hr><br></pre>';
    }

    public function showContent()
    {
        $start_end_pagination_array = $this->getPaginationContent();
        $start = $start_end_pagination_array[0];
        $end = $start_end_pagination_array[1];
        $pagination = $start_end_pagination_array[2];
//        $this->showArray($start_end_pagination_array);
        require_once ('template/view.php');


    }

    public function parseData(){
        for($i = $this->number_of_current_page; $i > $this->number_of_current_page - 5; $i--){
            $this->current_page = self::INITIAL_PAGE . 'index/' . $i;
//            echo '<br>';
//            echo $this->current_page;
//            echo '<br>';
//            $search_content = $this->getContentToParsing($this->current_page);
            $search_content = $this->getContentToParsing(self::$initial_path);
            $pattern = '/<div class="text">(.*)<\/div>/i';
            $match = preg_match_all($pattern, $search_content, $matches);
            foreach ($matches[0] as $list) {
                array_push($this->data, $list);
            }
        }
//        $this->showArray($this->data);
    }

    public function getPaginationContent() // we receive here two things: number of the current page, and a counted amount of the list which we are going to show;
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
                    $pag .= '<li><a href="?Cpag=' . ($j + 1) . '">' . ($j + 1) . '</a></li>';
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
}