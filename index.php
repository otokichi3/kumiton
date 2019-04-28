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
        $kumis_by_level[$level][] = $kumis;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>くみとん</title>
    <style type="text/css">
        /* body { background-image: url("./badminton.jpg"); } */
        table, th, td { border: 1px solid black; border-collapse: collapse; text-align: center; }
        td { padding: 0 50px; }
    </style>
</head>
<body>
    <h3>今回の参加者</h3>
    <table>
        <tr>
            <th>名前</th>
            <th>レベル</th>
        </tr>
        <?php foreach ($members1 as $name => $level): ?>
            <tr>
                <td><?= $name ?></td>
                <td><?= $level ?></td>
            </tr>
        <?php endforeach ?>
    </table>
    <h3>試合の組み合わせ</h3>
    <?php foreach ($kumis_by_level as $level => $kumis): ?>
        <?= sprintf('Level %d<br>', $level) ?>
        <hr>
        <?php foreach ($kumis as $key => $kumi): ?>
            <?php $game_cnt = 1; ?>
            <?php foreach ($kumi as $key2 => $value): ?>
                組<?= $game_cnt++; ?>
                <table class="court" style="background: lightgreen;">
                    <tr style="height: 50px;">
                        <td style="border-right: none;"><?= $value[0][0] ?></td>
                        <td style="border-left: none;"><?= $value[0][1] ?></td>
                    </tr>
                    <tr style="height: 50px;">
                        <td style="border-right: none;"><?= $value[1][0] ?></td>
                        <td style="border-left: none;"><?= $value[1][1] ?></td>
                    </tr>
                </table>
            <?php endforeach ?>
        <?php endforeach ?>
        <br>
        <br>
    <?php endforeach ?>
</body>
</html>