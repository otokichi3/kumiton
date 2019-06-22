    <div class="container">
        <?php if ( ! empty($add_member)): ?>
            <div class="alert alert-info">
                メンバーを追加しました。
            </div>
        <?php endif ?>
        <form action="<?= base_url('main/show_game') ?>" method="post">
            <div class="jumbotron">
                <h1 class="display-5"><?= $title ?></h1>
                <p class="lead"><?= $title_lead ?></p>
            </div>
			<div class="member_grid text-center">
				<?php foreach ($all_member_info as $key => $val): ?>
				<div class="align-middle husanka" data-name="<?= $val['name'] ?>"><?= $val['name'].'('.intval($val['level']).')' ?>
					<br><i class="fas <?= $val['sex'] === '1' ? 'fa-male my-blue' : 'fa-female my-pink' ?>"></i>
				</div>
				<?php endforeach ?>
			</div>
            <table class="table table-bordered table-hover table-sm table-responsive-md" style="display: none;">
                <caption>メンバーリスト</caption>
                <thead class="thead-light">
                    <tr>
                        <th scope="col">名前</th>
                        <th scope="col">レベル</th>
                        <th scope="col">参加<span class="pl-1"><input type="checkbox" name="check_all" id="check_all"></span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_member_info as $key => $val): ?>
                    <?php $checked = (array_search($val['name'], (array)$selected_member) !== FALSE) ? 'checked' : '' ?>
                    <tr>
                        <td><?= $val['name'] ?></td>
                        <td><?= intval($val['level']) ?></td>
                        <td>
                            <input type="checkbox" name="selected_member[]" value="<?= $val['name'] ?>" <?= $checked ?>>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm float-right" id="check_all2">全選択</button>
            <button type="submit" class="btn btn-primary btn-sm">次へ</button>
        </form>
