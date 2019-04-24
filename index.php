<?php
require_once('functions.php');

$test_members1 = ['タケさん' => '4', '大田先生' => '8', '吉崎広太' => '4', '藤井義博' => '3', '平山智子' => '3', '中下敬識' => '5', '加藤大' => '7'];

$all_pairs = get_all_pairs($test_members1);
// dump($all_pairs);

$sum_list = get_all_sum($all_pairs);
asort($sum_list);
$sum_numbers = array_unique(array_values($sum_list));
echo 'レベル合計値リスト：'.implode(', ', $sum_numbers);
echo '<br>';
$pairs_per_level = [];
foreach ($sum_numbers as $key => $val) {
    // echo '<ul>レベル合計['.$val.']のペア';
    foreach ($sum_list as $key2 => $val2) {
        if ($val2 === $val) {
            $pairs_per_level[$val][] = '<li>' .implode(' － ', array_keys($all_pairs[$key2])).'</li>';
        }
    }
    // echo '</ul>';
}
// dump($pairs_per_level);
echo '<ul>';
foreach ($pairs_per_level as $level => $pairs) {
    echo 'Level: '.$level.' '.implode(' - ', $pairs);
}
echo '</ul>';
