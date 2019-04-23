<?php
require_once('functions.php');

$test_members1 = ['大田先生' => '8', '吉崎広太' => '4', '藤井義博' => '3', '平山智子' => '3', '中下敬識' => '5'];

$all_pairs = get_all_pairs($test_members1);
// dump($all_pairs);

$sum_list = get_all_sum($all_pairs);
asort($sum_list);
echo 'SUM-LIST:';
dump($sum_list);
// foreach ($sum_list as $key => $sum) {
//     dump($all_pairs[$key]);
// }
