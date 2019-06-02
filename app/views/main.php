    <div class="container">
        <form action="/main" method="post">
            <?php foreach ($selected_list as $member): ?>
                <input type="hidden" name="selected_member[]" value="<?= $member ?>">
            <?php endforeach ?>
            <input type="submit" class="btn btn-info btn-sm" value="戻る">
        </form>
        <br>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#sanka_list">今回の参加者</button>
        <div id="sanka_list" class="collapse">
            <ul class="list-group list-group-horizontal-md overflow-auto text-center" style="max-height: 300px;">
                <?php foreach ($sanka_list as $name => $level): ?>
                <li class="list-group-item"><?= $name ?>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <hr>
        <h3>試合の組み合わせ</h3>
        <div id="court_list" class="cssgrid text-center">
            <?= $court_view ?>
        </div>
        <button type="button" class="btn btn-primary" id="next_match">次の組み合わせ</button>
        <section>
