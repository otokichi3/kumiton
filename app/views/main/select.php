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
            <div class="form-inline">
                <label for="summarize" class="mr-2">レベルの段階</label>
                <select id="summarize" name="summarize" class="form-control" style="width: 100px;">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
			<div class="member_grid text-center">
				<?php foreach ($all_member_info as $key => $val): ?>
				<div class="align-middle husanka" data-name="<?= $val['name'] ?>"><?= $val['name'].'('.intval($val['level']).')' ?>
					<br><i class="fas <?= $val['sex'] === '1' ? 'fa-male my-blue' : 'fa-female my-pink' ?>"></i>
				</div>
				<?php endforeach ?>
			</div>
            <button type="button" class="btn btn-primary float-right" id="check_all2">全選択</button>
            <button type="submit" class="btn btn-primary ">次へ</button>
        </form>
