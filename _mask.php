<?php
namespace MaskHandle;

class Mask {
    protected $head, $list;
    public function __construct($csv){
        $head = array_shift($csv);
        $this->head = $head;
        $this->list = $csv;
    }
    private function wordSort($sortingKey, $searchWord, $maskNum){
        $printList = array();
        if($searchWord){
            foreach($this->list as $val){
                if(strpos($val[$sortingKey], $searchWord) !== false && $val[3] >= $maskNum){
                    $printList[] = $val;
                }
            }
        }
        return $printList;
    }
    public function wordAndNumberSort($sortingKey, $searchWord, $maskNum){
        $printList = self::wordSort($sortingKey, $searchWord, $maskNum);
        usort($printList, function($sortingKey, $searchWord){
            if ($sortingKey[3] == $searchWord[3]) {
                return 0;
            }
            return ($sortingKey[3] < $searchWord[3]) ? -1 : 1;
        });
        array_unshift($printList, $this->head);
        return $printList;
    }
}
