<?php
require_once('functions.php');
define('FILE_SANKA', 'sanka_member.json');
define('FILE_HUSANKA', 'husanka_member.json');

// $selected_member = $_POST['selected_member'];
$selected_member = filter_input(INPUT_POST, 'selected_member', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
if ( ! is_array($selected_member)  || count($selected_member) < 4) {
    echo('参加者は４人以上必要です。');
    echo '<a href="index.php">戻る</a>';
    die;
}

$sanka_data = json_decode(file_get_contents(FILE_SANKA), true);
$husanka_data = json_decode(file_get_contents(FILE_HUSANKA), true);
$all_member = $sanka_data + $husanka_data;
asort($all_member);

// 参加者
$all_member_name = array_keys($all_member);
$sanka = array_intersect($all_member_name, $selected_member);

// 参加者（名前ーレベル形式）
$sanka_member = get_member($selected_member, $all_member, true);

$sanka_json = json_encode($sanka_member);
if (file_put_contents(FILE_SANKA, $sanka_json) === FALSE) {
    echo 'Failed to write.';
}

// 不参加者
$husanka_member = get_member($selected_member, $all_member, false);
$husanka_json = json_encode($husanka_member);
if (file_put_contents(FILE_HUSANKA, $husanka_json) === FALSE) {
    echo 'Failed to write.';
}


// 全ペア算出
$all_pairs = get_all_pairs($sanka_member);

// 全ペアのレベル合計値算出
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
    <title>くみとん</title>
    <!-- <link rel="stylesheet" type="text/css" href="style.css" media="screen, tv"> -->
    <style type="text/css">
        /* body { background-image: url("./badminton.jpg"); } */
        table, th, td { border: 1px solid black; border-collapse: collapse; text-align: center; }
        td { padding: 0 50px; }
    </style>
</head>
<body>
    <form action="index.php" method="post">
        <?php foreach ($selected_member as $member): ?>
            <input type="hidden" name="selected_member[]" value="<?= $member ?>">
        <?php endforeach ?>
        <input type="submit" value="戻る">
    </form>
    <br>
    <a href="./text.php" target="_blank">テキスト版</a>
    <h3>今回の参加者</h3>
    <table>
        <tr>
            <th>名前</th>
            <th>レベル</th>
        </tr>
        <?php foreach ($sanka_member as $name => $level): ?>
            <tr>
                <td><?= $name ?></td>
                <td><?= $level ?></td>
            </tr>
        <?php endforeach ?>
    </table>
    <h3>試合の組み合わせ</h3>
    <?php foreach ($kumis_by_level as $level => $kumi): ?>
        <?= sprintf('レベル%d(%d)<br><hr>', $level, count($kumi)) ?>
        <?php $game_cnt = 1; ?>
        <?php foreach ($kumi as $key => $pair): ?>
            組<?= $game_cnt++; ?>
            <table class="court" style="background: lightgreen;">
                <tr style="height: 50px;">
                    <td style="border-right: none;"><?= $pair[0][0] ?></td>
                    <td style="border-left: none;"><?=  $pair[0][1] ?></td>
                </tr>
                <tr style="height: 50px;">
                    <td style="border-right: none;"><?= $pair[1][0] ?></td>
                    <td style="border-left: none;"><?=  $pair[1][1] ?></td>
                </tr>
            </table>
        <?php endforeach ?>
        <br>
        <br>
    <?php endforeach ?>
</body>
</html>