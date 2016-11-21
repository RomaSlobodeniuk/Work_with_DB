<?php
/**
 * // WARNING: This class isn't completed!
 * User: With the best regards, ROMA SLOBODENIUK
 */

namespace Parsing;

use Databases_classes\Initialization;
use Databases_classes\Create;
use Databases_classes\Edit;
use Databases_classes\DB_List;
use Parsing\Parse;

class ParseLinks extends Parse
{
    const INITIAL_PAGE = 'http://zefirka.net/';
//    public static $initial_path = __DIR__ . '\data\input_data.txt';
    public $number_of_current_page;
    public $number_of_last_page;
    public $current_page;
//    public $page_array = [];
    public $data = [];
    const CURRENT_PAGE = 1;
    const PER_PAGE = 10;

    public function __construct()
    {
        $this->setCurrentPage(self::INITIAL_PAGE);
        $this->setLastPage(self::INITIAL_PAGE);
        $this->parseData(self::INITIAL_PAGE);
        $this->showContent();
    }

    public function getContentForParsing($file_destination)
    {
//        return iconv('CP1251', 'UTF-8', file_get_contents($file_destination, true));
        return file_get_contents($file_destination, true);
    }

    public function setCurrentPage($initial_path)
    {
        $search_content = $this->getContentForParsing($initial_path);
//        echo $search_content;
        $pattern = '/<span class=\'page-numbers current\'>([0-9]{0,5})<\/span>/i';
        $match = preg_match($pattern, $search_content, $matches);
//        self::showArray($matches);
//        die;

        $this->number_of_current_page = $matches[1]; // We search for the value of the current page, then we'll use it in the function "parseData";
    }

    public function setLastPage($initial_path)
    {
        $search_content = $this->getContentForParsing($initial_path);
//        echo $search_content;
        $pattern = '/<a class=\'page-numbers\' href=\'(.*)\'>([0-9]{0,5})<\/a>/i';
        $match = preg_match_all($pattern, $search_content, $matches);
//        self::showArray($matches);
//        die;
        $this->number_of_last_page = $matches[2][2]; // We search for the value of the current page, then we'll use it in the function "parseData";
//        echo 'The last page is: ' . $this->number_of_last_page;
    }

    public function parseData($path_for_parsing)
    {
        $temp_array_with_comments = array();
        $matches_with_links = array();
        $matches_number_of_comments = array();
        for ($i = $this->number_of_current_page, $j = 0; /*$i < $this->number_of_last_page*/
             $j < 4; $i++) {
            $this->current_page = $path_for_parsing . 'page/' . $i;
            array_push($this->page_array, $this->current_page);
            $search_content = $this->getContentForParsing($this->current_page);
            $pattern = '/<span class="post-info-comments">(.*)<\/span>/i';
            $match = preg_match_all($pattern, $search_content, $matches_1st);


            $search_content_2 = '';
            foreach ($matches_1st[1] as $value) {
                $search_content_2 .= $value;
            }

            $pattern = '/>([0-9]{0,}) комментари/i';
            $match = preg_match_all($pattern, $search_content_2, $matches_2nd);

            $tmp_matches_with_links = $matches_1st[1];
            $tmp_matches_number_of_comments = $matches_2nd[1];

            foreach ($tmp_matches_number_of_comments as $value){
                if($value == 0){
                } elseif ($value > 0){
                    $j++;
//                    echo '$j = ' . $j . '<br>';
                }
            }
            foreach ($tmp_matches_with_links as $list) {
                array_push($matches_with_links, $list);
            }
            foreach ($tmp_matches_number_of_comments as $list) {
                array_push($matches_number_of_comments, $list);
            }
        }
//        self::showArray($this->page_array);
//        self::showArray($matches_with_links);
//        self::showArray($matches_number_of_comments);

        $temp_array_with_comments = $this->pushComment($matches_number_of_comments, $matches_with_links);
        self::showArray($temp_array_with_comments);

        $z = 0;
        foreach ($temp_array_with_comments as $key => $value){
            $pattern1 = '/[\d]{0,5} -> This is the link to comments: <a href="(.*)#comments">1 комментарий<\/a> with "([\d]{0,5})" comments/i';
            $match = preg_match($pattern1, $value, $match1);
//            self::showArray($match1);

            $search_content = $this->getContentForParsing($match1[1]);

            $pattern2 = '/<h1 class="content-headline">(.*)<\/h1>/i';
            $match = preg_match($pattern2, $search_content, $match2);
//            self::showArray($match2);
            $this->data[$z]['headline'] = $match2[1];

            $pattern3 = '/<img class="" src="(.*)" alt="'. $this->data[$z]['headline'] . '".*\/>/i';
            $match = preg_match($pattern3, $search_content, $match3);
            $this->data[$z]['headline_img_src'] = $match3[1];

            $pattern4 = '/<div class="wc-comment-text"><p>(.*)<\/p>/i';
            $match = preg_match_all($pattern4, $search_content, $match4);
//            $this->data[$z][''] = $match4;
            $this->data[$z]['comments'] = $match4[1];
//            self::showArray($match4);
            $z++;
//            echo $search_content;
        }
        self::showArray($this->data);
    }

    public function showContent()
    {
        $start_end_pagination_array = $this->getPaginationContent(); // We're preparing the pagination data here which will be used in the cycle "for" in a "view.php";
        $start = $start_end_pagination_array[0];
        $end = $start_end_pagination_array[1];
        $pagination = $start_end_pagination_array[2];

        require_once('template/viewParseLinksToGetComments.php');
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

    public function showArray($array)
    {
        echo '<hr><br><pre>';
        var_export($array);
        echo '<hr><br></pre>';
    }

    public function pushComment($matches_number_of_comments, $matches_with_links){
        $temp_array_with_comments = array();
        foreach ($matches_number_of_comments as $key => $value) {
            switch ($value):
                case 0:
                    break;
                case !0:
                    $comment = "{$key} -> This is the link to comments: {$matches_with_links[$key]} with \"{$value}\" comments";

                    $checking_string = false;
                    if (!empty($temp_array_with_comments)) {
                        foreach ($temp_array_with_comments as $com) {
                            if ($com == $comment) {
                                $checking_string = false;
                                break;
                            } else {
                                $checking_string = true;
                            }
                        }
                        if ($checking_string) {
                            array_push($temp_array_with_comments, $comment);
                        }
                    } elseif (empty($temp_array_with_comments)) {
                        array_push($temp_array_with_comments, $comment);
                    }
                    break;
            endswitch;
        }
        return $temp_array_with_comments;
    }
}