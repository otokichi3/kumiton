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
        <table class="table table-bordered table-hover table-sm table-responsive-md">
            <thead class="thead-light">
                <tr>
                    <th>名前</th>
                    <th>レベル</th>
                </tr>
            </thead>
            <?php foreach ($sanka_list as $name => $level): ?>
                <tr>
                    <td><?= $name ?></td>
                    <td><?= $level ?></td>
                </tr>
            <?php endforeach ?>
        </table>
        <h3>試合の組み合わせ</h3>
        <?php /* foreach ($match_list as $level => $kumi): ?>
            <?= sprintf('レベル%d(%d)<br><hr>', $level, count($kumi)) ?>
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
        <?php endforeach */ ?>
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
