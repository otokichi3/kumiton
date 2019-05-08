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
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <a href="#" class="navbar-brand">組みとん</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu1" aria-controls="navmenu1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navmenu1">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="">メンバー</a>
                <a class="nav-item nav-link" href="#">試合</a>
                <a class="nav-item nav-link" href="#">履歴</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php if ( ! empty($add_member)): ?>
            <div class="alert alert-info">
                メンバーを追加しました。
            </div>
        <?php endif ?>
        <form action="main.php" method="post">
        <div class="jumbotron">
            <h1 class="display-5">メンバー</h1>
            <p class="lead">メンバーの選択、追加、削除を行えます。</p>
        </div>
            <table class="table table-bordered table-hover table-sm table-responsive-md">
                <caption>メンバーリスト</caption>
                <thead class="thead-light">
                    <tr>
                        <th scope="col">名前</th>
                        <th scope="col">レベル</th>
                        <th scope="col">参加</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_member as $name => $level): ?>
                    <?php $checked = (array_search($name, (array)$selected_member) !== false) ? 'checked' : '' ?>
                    <tr>
                        <td><?= $name ?>
                        </td>
                        <td><?= $level ?>
                        </td>
                        <td>
                            <input type="checkbox" name="selected_member[]" value="<?= $name ?>" <?= $checked ?>>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
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
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
