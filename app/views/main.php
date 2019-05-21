    <div class="container">
        <form action="<?= base_url('main') ?>" method="post">
            <?php foreach ($selected_list as $member): ?>
                <input type="hidden" name="selected_member[]" value="<?= $member ?>">
            <?php endforeach ?>
            <input type="submit" value="戻る">
        </form>
        <br>
        <a href="./text.php" target="_blank">テキスト版</a>
        <h3>今回の参加者</h3>
        <ul class="list-group list-group-horizontal-md overflow-auto" style="max-height: 300px;">
            <?php foreach ($sanka_list as $name => $level): ?>
                <li class="list-group-item"><?= $name ?></li>
            <?php endforeach ?>
        </ul>
        <br>
        <h3>試合の組み合わせ</h3>
        <div class="cssgrid">
            <?php foreach ($match as $pair): ?>
                <div>
                    <table class="court">
                        <tr style="border-bottom: 1px solid black">
                            <td class="text-center"><?= $pair['server1'] ?></td>
                            <td class="text-center"><?= $pair['server2'] ?></td>
                        </tr>
                        <tr>
                            <td class="text-center"><?= $pair['receiver1'] ?></td>
                            <td class="text-center"><?= $pair['receiver2'] ?></td>
                        </tr>
                    </table>
                </div>
            <?php endforeach ?>
        </div>
