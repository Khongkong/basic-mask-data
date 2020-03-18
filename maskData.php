<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/_mask.php";
use League\CLImate\CLImate;
use MaskHandle\Mask;

// read the csv file
$url = 'http://data.nhi.gov.tw/Datasets/Download.ashx?rid=A21030000I-D50001-001&l=https://data.nhi.gov.tw/resource/mask/maskdata.csv';
$raw_file = file_get_contents($url);

// put csv into array
$mask = new Mask($raw_file);
$csv = $mask->getCsv();

// 搜尋機制
$climate = new CLImate;
$sortingOptions = ['機構名稱', '地址'];
$openInput = $climate->radio('歡迎使用剩餘口罩查詢系統，請選擇您要依據何種資訊查詢。', $sortingOptions);
$sortingMethod = $openInput->prompt();
$sortingKey = array_search($sortingMethod, $sortingOptions);

$sortingInput = $climate->input("請輸入". $sortingMethod. "關鍵字搜尋。\n");
$searchWord = $sortingInput->prompt();

$maskNum = 0;
if($sortingMethod == '地址'){
    $maskNumOptions = [1000, 100, 50, 10, "無需限制"];
    $numInput = $climate->radio('該機構剩下多少口罩（含以上）。', $maskNumOptions);
    $maskNum = (int)$numInput->prompt();
}

// sorting 篩選結果
$printList = $mask->wordAndNumberSort($sortingKey, $searchWord, $maskNum);

// print out
echo "\n". "搜尋結果:". "\n";
if(count($printList) > 1){
    $climate->table($printList);
}else{
    echo "查無符合的機構";
}

