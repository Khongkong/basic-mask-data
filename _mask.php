<?php
namespace MaskHandle;

class Mask {
    protected $head, $csv;
    public function __construct($raw_file){
        $csv = array_map(function($val){
            return array_slice($val, 1, 4);
        }, array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $raw_file)));
        array_pop($csv);
        $head = array_shift($csv);
        
        $this->head = $head;
        $this->csv = $csv;
    }
    private function wordSearch($sortingKey, $searchWord, $maskNum){
        $printList = array();
        if($searchWord){
            foreach($this->csv as $val){
                if(strpos($val[$sortingKey], $searchWord) !== false && $val[3] >= $maskNum){
                    $printList[] = $val;
                }
            }
        }
        return $printList;
    }
    public function wordAndNumberSort($sortingKey, $searchWord, $maskNum){
        $printList = self::wordSearch($sortingKey, $searchWord, $maskNum);
        usort($printList, function($sortingKey, $searchWord){
            if ($sortingKey[3] == $searchWord[3]) {
                return 0;
            }
            return ($sortingKey[3] > $searchWord[3]) ? -1 : 1;
        });
        array_unshift($printList, $this->head);
        return $printList;
    }
    public function getCsv(){
        return $this->csv;
    }
}
