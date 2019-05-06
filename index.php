<?php
require_once('functions.php');
// require_once('data.php');

$sanka_file = 'sanka_member.json';
$sanka_data = file_get_contents($sanka_file);
$sanka_member = json_decode($sanka_data, true);

// メンバー追加
$add_member = [];
$add_member_name = filter_input(INPUT_POST, 'add_member_name', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
// dump($add_member_name);
$add_member_level = filter_input(INPUT_POST, 'add_member_level', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
if ( ! is_null(add_member_name) && ! is_null($add_member_level)) {
    foreach ($add_member_name as $key => $value) {
        if ( ! $value) {
            unset($add_member_name[$key]);
            unset($add_member_level[$key]);
        }
    }
    $add_member = array_combine($add_member_name, $add_member_level);
    $sanka_member += $add_member;
    if (file_put_contents($sanka_file, json_encode($sanka_member)) === FALSE) {
        echo 'Failed to write.';
    }
}

$husanka_file = 'husanka_member.json';
$husanka_data = file_get_contents($husanka_file);
$husanka_member = json_decode($husanka_data, true);

// $file = 'all_member.json';
// $content = file_get_contents($file);
// $all_member = json_decode($content, true);
$all_member = $sanka_member + $husanka_member;
asort($all_member);
// dump($all_member);



$selected_member = filter_input(INPUT_POST, 'selected_member', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
// if (file_put_contents($file, json_encode($member)) === FALSE) {
//     echo 'Failed to write.';
// }
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
    <?php if ( ! empty($add_member)): ?>
        <p><strong style="color: red;">メンバーを追加しました。</strong></p>
    <?php endif ?>
    <form action="main.php" method="post">
        <h3>メンバー一覧</h3>
        <table>
            <tr>
                <th>名前</th>
                <th>レベル</th>
                <th>参加</th>
            </tr>
            <?php foreach ($all_member as $name => $level): ?>
            <?php $checked = (array_search($name, (array)$selected_member) !== false) ? 'checked' : '' ?>
                <tr>
                    <td><?= $name ?></td>
                    <td><?= $level ?></td>
                    <td>
                        <input type="checkbox" name="selected_member[]" value="<?= $name ?>" <?= $checked ?>>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <button type="submit">次へ</button>
    </form>

    <form action="index.php" method="post">
        <h3>メンバー追加</h3>
        <table>
            <tr>
                <th>名前</th>
                <th>レベル</th>
            </tr>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <tr>
                    <td>
                        <input type="text" name="add_member_name[]">
                    </td>
                    <td>
                        <select name="add_member_level[]">
                            <option value="0" selected>選択してください</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
            <?php endfor ?>
        </table>
        <button type="submit">追加</button>
    </form>
</body>
</html>