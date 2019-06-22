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