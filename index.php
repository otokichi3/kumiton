<style type="text/css">
    table, th, td { border: 1px solid black; }
</style>

<?php
require_once('functions.php');
require_once('data.php');

// echo '<table>';
// echo '<tr>';
// echo '<th>名前</th>';
// echo '<th>レベル</th>';
// echo '</tr>';
// foreach ($members1 as $name => $level) {
//     echo '<tr>';
//     echo '<td>'. $name . '</td>';
//     echo '<td>'. $level . '</td>';
//     echo '</tr>';
// }
// echo '</table>';

$all_pairs = get_all_pairs($members1);

$sum_list = get_all_sum($all_pairs);
asort($sum_list);
$sum_numbers = array_unique(array_values($sum_list));
// echo 'レベル合計値リスト：'.implode(', ', $sum_numbers);
// echo '<br>';
// echo '<br>';
$pairs_by_level = get_pairs_by_level($all_pairs, $sum_list, $sum_numbers);
// foreach ($pairs_by_level as $level => $pairs) {
//     echo sprintf('Level %d', $level);
//     echo '<hr>';
//     echo '<ul>';
//     foreach ($pairs as $pair) {
//         echo sprintf('<li>%s</li>', implode('ー', $pair));
//     }
//     echo '</ul>';
// }

$level7 = $pairs_by_level[7];
// dump($level7);
// $test_keys = array_keys($level7);
// dump($test_keys);
// $kumis_by_level = get_kumis_by_level($test_keys);
$kumis_by_level = get_kumis_by_level($level7);
// dump($kumis_by_level);
foreach ($kumis_by_level as $key => $kumis) {
    echo sprintf('%d組目<br>', (int)$key + 1);
    foreach ($kumis as $key2 => $kumi) {
        echo sprintf('　ペア%d：%s<br>', (int)$key2 + 1, implode(' － ', $kumi));
    }

}