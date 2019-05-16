<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>くみとん</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <style>
    .cssgrid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        grid-auto-rows: 1fr;
        grid-gap: 0.8em;
        padding: 0.8em;
        margin: 0 0 2em;
    }

    .cssgrid > div {
        background: mediumaquamarine;
        overflow: auto;
        min-width: 0;
        /* padding: 1em; */
        height: 300px;
        max-width: 261px;
    }
    .court {
        width: 100%;
        height: 100%;
    }
    .maru_half {
        border-radius: 50%;
        font-size: 8em;
        line-height: 2em;
        padding-top: 0.1em;
        padding-left: 0.5em;
        padding-right: 0.5em;
    }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <a href="#" class="navbar-brand">組みとん</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu1" aria-controls="navmenu1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navmenu1">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="main">メンバー選択</a>
                <a class="nav-item nav-link" href="#">試合</a>
                <a class="nav-item nav-link" href="#">履歴</a>
                <a class="nav-item nav-link" href="todo">todo</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <form action="main" method="post">
            <?php foreach ($selected_member as $member): ?>
                <input type="hidden" name="selected_member[]" value="<?= $member ?>">
            <?php endforeach ?>
            <input type="submit" value="戻る">
        </form>
        <br>
        <a href="./text.php" target="_blank">テキスト版</a>
        <h3>今回の参加者</h3>
        <table class="table table-bordered table-hover table-sm table-responsive-md">
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
        <!-- <?php foreach ($kumis_by_level as $level => $kumi): ?>
            <?= sprintf('レベル%d(%d)<br><hr>', $level, count($kumi)) ?>
            <?php $game_cnt = 1; ?>
            <?php foreach ($kumi as $key => $pair): ?>
                組<?= $game_cnt++; ?>
                <table class="table table-bordered table-hover table-sm table-responsive-md w-50" style="background: yellowgreen;">
                    <tr>
                        <td><?= $pair[0][0] ?></td>
                        <td><?= $pair[0][1] ?></td>
                    </tr>
                    <tr>
                        <td><?= $pair[1][0] ?></td>
                        <td><?= $pair[1][1] ?></td>
                    </tr>
                </table>
            <?php endforeach ?>
            <br>
            <br>
        <?php endforeach ?> -->
        <?php foreach ($kumis_by_level as $level => $kumi): ?>
            <?= sprintf('レベル%d(%d)<br><hr>', $level, count($kumi)) ?>
            <?php $game_cnt = 1; ?>
            <div class="cssgrid">
                <?php foreach ($kumi as $key => $pair): ?>
                    <div>
                        <table class="court">
                            <tr style="border-bottom: 1px solid black">
                                <td class="text-center"><?= $pair[0][0] ?></td>
                                <td class="text-center"><?= $pair[0][1] ?></td>
                            </tr>
                            <tr>
                                <td class="text-center"><?= $pair[1][0] ?></td>
                                <td class="text-center"><?= $pair[1][1] ?></td>
                            </tr>
                        </table>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endforeach ?>
        <!-- <div class="cssgrid">
            <div>
                <span class="maru-half">S</span>
                <table class="court">
                    <tr style="border-bottom: 1px solid black">
                        <td class="text-center">a</td>
                        <td class="text-center">b</td>
                    </tr>
                    <tr>
                        <td class="text-center">c</td>
                        <td class="text-center">d</td>
                    </tr>
                </table>
            </div>
            <div></div>
            <div></div>
            <div></div>
        </div> -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="js/index.js"></script>
    </div>
</body>
</html>