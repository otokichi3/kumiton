<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>くみとん</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

</head>
<body>
    <form action="main" method="post">
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
        <?php foreach ($all_member as $name => $level): ?>
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