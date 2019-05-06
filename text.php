<?php
require_once('functions.php');
require_once('data.php');

$all_pairs = get_all_pairs($members1);

$sum_list = get_all_sum($all_pairs);
asort($sum_list);
$sum_numbers = array_unique(array_values($sum_list));
$pairs_by_level = get_pairs_by_level($all_pairs, $sum_list, $sum_numbers);

$kumis_by_level = [];
foreach ($pairs_by_level as $level => $pairs) {
    $kumis = get_kumis_by_level($pairs);
    if (count($kumis) === 0) {
        continue;
    } else {
        foreach ($kumis as $key => $val) {
            foreach ($val as $key2 => $val2) {
                $kumis_by_level[$level][] = $val2;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>くみとん(text)</title>
</head>
<body>
    <?php foreach ($kumis_by_level as $level => $kumi): ?>
        <?= sprintf('<b>レベル%d(%d)</b><br>', $level, count($kumi)) ?>
        <?php foreach ($kumi as $key => $pair): ?>
            <?= sprintf('□%s - %s vs %s - %s<br>', $pair[0][0], $pair[0][1], $pair[1][0], $pair[1][1]) ?>
        <?php endforeach ?>
    <?php endforeach ?>
</body>
</html>